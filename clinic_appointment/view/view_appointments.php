<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Manila');

require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();
$conn->exec("SET time_zone = '+08:00'");

$user_id = $_SESSION['user']['id'];

// Completed appointments with medical record
$stmt = $conn->prepare("SELECT mr.id, mr.symptoms, mr.first_aid, mr.remarks, mr.prescription, a.appointment_date AS date, a.appointment_time AS time
                        FROM medical_record mr
                        JOIN appointment a ON mr.appointment_id = a.id
                        WHERE mr.user_id = :user_id AND a.status = 'completed'
                        ORDER BY mr.id DESC");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$completedAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// All appointments for medical history
$stmt2 = $conn->prepare("SELECT id AS appointment_id, reason, appointment_date AS date, appointment_time AS time, status
                         FROM appointment
                         WHERE user_id = :user_id
                         ORDER BY appointment_date DESC, appointment_time DESC");
$stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->execute();
$historyAppointments = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link rel="stylesheet" href="login_design.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
        }

        .dashboard-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .history-section {
            margin-bottom: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #0077b6;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status {
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .status.pending {
            background-color: #ffeb3b;
            color: #000;
        }

        .status.completed {
            background-color: #4caf50;
            color: #fff;
        }

        .status.cancelled {
            background-color: #f44336;
            color: #fff;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #0077b6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .logout-btn:hover {
            background-color: #005b8f;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Hello, <?php echo $_SESSION["user"]["name"]; ?>!</h2>
    <p>Here's a summary of your appointments and medical records.</p>
</div>

<!-- Medical History Table -->
<div class="history-section">
    <h3>Medical History (All Appointments)</h3>
    <?php if (count($historyAppointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historyAppointments as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['appointment_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                        <td><?php echo date('H:i', strtotime($row['time'])); ?></td>
                        <td>
                            <span class="status <?php echo strtolower($row['status']); ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointment history available.</p>
    <?php endif; ?>
</div>

<a href="user_dashboard.php" class="logout-btn">Back to Dashboard</a>

</body>
</html>
