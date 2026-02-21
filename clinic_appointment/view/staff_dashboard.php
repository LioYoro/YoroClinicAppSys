<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php'; 

// Set the time zone to ensure accurate date comparisons
date_default_timezone_set('Asia/Manila');  // Adjust to your local time zone

$db = new Database();        // Create instance of your class
$pdo = $db->getConnection();
$pdo->exec("SET time_zone = '+08:00'");


// Get today's date in Y-m-d format
$dateToday = date('Y-m-d');

// Prepare SQL query to get appointments for today
$stmt = $pdo->prepare("
    SELECT a.id, u.name, a.reason, a.status
    FROM appointment a
    JOIN user u ON a.user_id = u.id
    WHERE DATE(a.appointment_date) = ? AND a.status = 'active'
    ORDER BY a.id ASC
");


$stmt->execute([$dateToday]);
$appointments = $stmt->fetchAll();


// Debug: Output today's date and fetched appointments for verification
echo "Today's Date: " . $dateToday . "<br>";
// var_dump($appointments);  // See the raw data fetched by the query

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor and Clinic Staff Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css"> <!-- Use external CSS -->
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
    <!-- Header Section -->
    <div class="header">
        <div class="system-name">JRU Online Clinic Appointment</div>
        <div class="user-type">
            <?php echo ucfirst($_SESSION["user"]["role"]); ?>
        </div>
        <a href="../controller/AccountController.php?logout=true" class="logout-btn">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="dashboard-container">
        <p class="dashboard-msg">Welcome to JRU Clinic!</p>
        <h2>Hello, Doctor <?php echo $_SESSION["user"]["name"]; ?>!</h2>
        <p>Here is today's appointment queue:</p>

        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments) > 0): ?>
                    <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td><?= htmlspecialchars($appt['id']) ?></td>
                            <td><?= htmlspecialchars($appt['name']) ?></td>
                            <td><?= htmlspecialchars($appt['reason']) ?></td>
                            <td><?= htmlspecialchars($appt['status']) ?></td>
                            <td><a href="checkup_form.php?appointment_id=<?= $appt['id'] ?>">Check-up</a></td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No appointments for today.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>