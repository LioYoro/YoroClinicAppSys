<?php

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$appointment_id = isset($_GET['appointment_id']) ? $_GET['appointment_id'] : null;
if (!$appointment_id) {
    // Redirect if no appointment ID is provided
    header("Location: doctor_dashboard.php");
    exit();
}

$db = new Database();
$pdo = $db->getConnection();
$pdo->exec("SET time_zone = '+08:00'");


// Fetch appointment details
$stmt = $pdo->prepare("SELECT a.id, u.name, a.appointment_date FROM appointment a JOIN user u ON a.user_id = u.id WHERE a.id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

// Check if appointment exists
if (!$appointment) {
    echo "Appointment not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the check-up form submission

    // Get the form data
    $symptoms = $_POST['symptoms'];
    $first_aid = $_POST['first_aid'];
    $remarks = $_POST['remarks'];
    $prescription = $_POST['prescription'];

    // Insert into the medical_record table
    $stmt = $pdo->prepare("INSERT INTO medical_record (appointment_id, user_id, doctor_id, symptoms, first_aid, remarks, prescription)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $appointment_id,
        $appointment['user_id'], // assuming user_id is fetched correctly
        $_SESSION["user"]["id"], // assuming doctor ID is stored in session
        $symptoms,
        $first_aid,
        $remarks,
        $prescription
    ]);

    // Update the appointment status to 'done'
    $stmt = $pdo->prepare("UPDATE appointment SET status = 'done' WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // Send the prescription to the patient
    // Assuming a sendEmail function exists to send the email
    sendEmail($appointment['user_id'], $prescription);

    echo "Check-up completed and prescription sent!";
    header("Location: doctor_dashboard.php"); // Redirect to the dashboard
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-up Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            text-align: center;
            image-rendering: crisp-edges;
            opacity: 0;
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .header .system-name {
            font-family: 'Futura', sans-serif;
            font-size: 28px;
            font-weight: bold;
        }

        .header .user-type {
            font-family: 'Futura', sans-serif;
            font-size: 16px;
            font-weight: bold;
            background-color: #0D2B66;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
        }

        .dashboard-container {
            margin-top: 120px; /* Space for header */
            background-color: #f8f8ff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 50px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            animation: slideDown 1s ease-in-out forwards 0.5s;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #0D2B66;
            color: white;
        }

        .action-form {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-form select, .action-form button {
            padding: 6px 10px;
            font-size: 14px;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #0D2B66;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #ECAA05;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(236, 170, 5, 0.7);
        }

        .back-btn, .submit-btn {
            padding: 12px 25px;
            background-color: #0D2B66;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
            font-weight: bold;
            margin-top: 20px;
        }

        .back-btn:hover, .submit-btn:hover {
            background-color: #ECAA05;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(236, 170, 5, 0.7);
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <h1>Check-up for Appointment ID: <?= htmlspecialchars($appointment['id']) ?></h1>

    <div class="form-container">
        <form method="POST" action="process_checkup.php">
            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>">

            <label for="symptoms">Symptoms:</label>
            <textarea name="symptoms" id="symptoms" required></textarea><br><br>

            <label for="first_aid">First Aid:</label>
            <textarea name="first_aid" id="first_aid"></textarea><br><br>

            <label for="remarks">Remarks:</label>
            <textarea name="remarks" id="remarks"></textarea><br><br>

            <label for="prescription">Prescription:</label>
            <textarea name="prescription" id="prescription" required></textarea><br><br>

            <!-- Optional: Generate Medical Certificate -->
            <label for="generate_certificate">Generate Medical Certificate:</label>
            <input type="checkbox" name="generate_certificate" id="generate_certificate" value="1"><br>

            <button type="submit" class="submit-btn">Submit Check-up</button>
        </form>

        <!-- Back button -->
        <a href="../view/staff_dashboard.php">
            <button class="back-btn">Back to Dashboard</button>
        </a>
    </div>
</body>
</html>
