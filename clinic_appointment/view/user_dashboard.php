<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Assuming database connection is established
$user_id = $_SESSION['user']['id'];  // Get the user_id from session or elsewhere

// Fetch the user's current appointments
$stmt = $conn->prepare("SELECT id AS appointment_id, reason, appointment_date AS date, appointment_time AS time, status
                        FROM appointment 
                        WHERE user_id = :user_id 
                        ORDER BY appointment_id DESC LIMIT 0, 25");

$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);  // Bind the user_id parameter
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt_history = $conn->prepare("SELECT mr.symptoms, mr.first_aid, mr.remarks, mr.prescription, mr.created_at, a.status 
                                FROM medical_record mr
                                JOIN appointment a ON mr.appointment_id = a.id
                                WHERE mr.user_id = :user_id AND a.user_id = :user_id 
                                AND a.status = 'done'");


$stmt_history->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_history->execute();

$history_results = $stmt_history->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="login_design.css">
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
            padding-top: 120px;
            min-height: 100vh;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'Futura';
            src: url('fonts/Futura-Medium.woff') format('woff');
            font-weight: normal;
            font-style: normal;
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

        .dashboard-container, .info-container, .appointment-container, .history-container {
            margin: 120px auto 20px auto;
            background-color: #f8f8ff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 85%;
            max-width: 900px;
            transition: 0.3s;
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
        }

        .dashboard-container:hover,
        .info-container:hover,
        .appointment-container:hover,
        .history-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .dashboard-msg {
            font-family: 'Futura', sans-serif;
            font-size: 40px;
            color: #0D2B66;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            font-family: 'Futura', sans-serif;
            font-size: 28px;
            color: #0D2B66;
            margin-bottom: 10px;
        }

        .info-container p, .appointment-container p, .history-container p {
            font-size: 18px;
            color: #333;
            line-height: 1.6;
        }

        .logout-btn {
            width: 100%;
            padding: 16px;
            background-color: #0D2B66;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
            font-weight: bold;
            display: block;
            margin-top: 20px;
            text-decoration: none;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #ECAA05;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(236, 170, 5, 0.7);
        }

        .logout-btn:active {
            transform: scale(0.95);
            box-shadow: 0 0 10px rgba(236, 170, 5, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .history-container {
    margin: 30px auto;
    background-color: #f8f8ff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 85%;
    max-width: 900px;
    min-height: 400px;
    max-height: 800px;
    overflow-y: auto;
    transition: 0.3s;
    opacity: 0;
    animation: fadeIn 1s ease-in-out forwards;
}

.history-entry {
    padding: 25px;
    background-color: #ffffff;
    border-left: 6px solid #0D2B66;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    text-align: left;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}


        .history-entry:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .history-entry p {
            margin: 6px 0;
            font-size: 16px;
            color: #333;
        }

        .status {
            display: inline-block;
            padding: 8px 15px;
            font-size: 16px;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
        }

        .status.pending {
            background-color: #FFA500;
        }

        .status.completed {
            background-color: #28A745;
        }

        .status.cancelled {
            background-color: #DC3545;
        }
    </style>
</head>
<body>

<!-- Header Section -->
<div class="header">
    <div class="system-name">JRU Online Clinic Appointment</div>
    <div class="user-type">
        <?php echo ucfirst($_SESSION["user"]["role"]); ?>
    </div>
</div>

<!-- Main Dashboard Section -->
<div class="dashboard-container"> 
    <p class="dashboard-msg">Welcome to JRU Clinic!</p>
    <h2>Hello, <?php echo $_SESSION["user"]["name"]; ?>!</h2>
    <p>Manage your clinic appointments, view your medical history, and keep track of your health records.</p>
</div>

<!-- Appointment Section -->
<div class="appointment-container">
    <h3>Appointments</h3>
    <a href="create_appointment.php" class="logout-btn" style="margin-top: 30px;">Create Appointment</a>
    <a href="reschedule_appointment.php" class="logout-btn" style="margin-top: 30px;">Reschedule Appointment</a>
    <a href="cancel_appointment.php" class="logout-btn" style="margin-top: 30px;">Cancel Appointment</a>
    <a href="view_appointments.php" class="logout-btn" style="margin-top: 30px;">View Appointments</a> <!-- View Appointments Button -->
</div>

<!-- Medical History Section -->
<div class="history-container">
    <h3>Medical History</h3>
    <?php if (count($history_results) > 0): ?>
        <?php foreach ($history_results as $record): ?>
            <div class="history-entry">
                <p><strong>Check-up Completed On:</strong> <?php echo date('F j, Y', strtotime($record['created_at'])); ?></p>
                <p><strong>Symptoms:</strong> <?php echo htmlspecialchars($record['symptoms']); ?></p>
                <p><strong>First Aid:</strong> <?php echo htmlspecialchars($record['first_aid']); ?></p>
                <p><strong>Remarks:</strong> <?php echo htmlspecialchars($record['remarks']); ?></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($record['prescription']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No completed medical records available.</p>
    <?php endif; ?>
</div>

<!-- Logout Button -->
<a href="../controller/AccountController.php?logout=true" class="logout-btn">Logout</a>

</body>
</html>
