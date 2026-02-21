<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$db = new Database();
$pdo = $db->getConnection();

// Fetch accounts grouped by role
function fetchAccountsByRole($pdo, $table, $role) {
    $sql = "SELECT id AS account_id, name, email FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($accounts as &$acc) {
        $acc['role'] = $role;
    }
    return $accounts;
}

$adminAccounts = fetchAccountsByRole($pdo, 'admin', 'admin');
$doctorAccounts = fetchAccountsByRole($pdo, 'doctor', 'doctor');
$userAccounts = fetchAccountsByRole($pdo, 'user', 'user');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grouped Accounts - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #0D2B66;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #0D2B66;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .back-btn {
            background: #ECAA05;
            color: #0D2B66;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-btn:hover {
            background: #0D2B66;
            color: #ECAA05;
            transform: scale(1.05);
        }
        .content {
            padding: 20px 40px;
        }
        h2 {
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #0D2B66;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            .back-btn {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>All Accounts Grouped by Role</h1>
    <a href="admin_dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="content">

    <!-- Admin Table -->
    <h2>Admin Accounts</h2>
    <?php if ($adminAccounts): ?>
    <table>
        <thead>
            <tr>
                <th>Account ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($adminAccounts as $account): ?>
            <tr>
                <td><?= htmlspecialchars($account['account_id']) ?></td>
                <td><?= htmlspecialchars($account['name']) ?></td>
                <td><?= htmlspecialchars($account['email']) ?></td>
                <td><?= htmlspecialchars($account['role']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No admin accounts found.</p>
    <?php endif; ?>

    <!-- Doctor Table -->
    <h2>Doctor Accounts</h2>
    <?php if ($doctorAccounts): ?>
    <table>
        <thead>
            <tr>
                <th>Account ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctorAccounts as $account): ?>
            <tr>
                <td><?= htmlspecialchars($account['account_id']) ?></td>
                <td><?= htmlspecialchars($account['name']) ?></td>
                <td><?= htmlspecialchars($account['email']) ?></td>
                <td><?= htmlspecialchars($account['role']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No doctor accounts found.</p>
    <?php endif; ?>

    <!-- User Table -->
    <h2>User Accounts</h2>
    <?php if ($userAccounts): ?>
    <table>
        <thead>
            <tr>
                <th>Account ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userAccounts as $account): ?>
            <tr>
                <td><?= htmlspecialchars($account['account_id']) ?></td>
                <td><?= htmlspecialchars($account['name']) ?></td>
                <td><?= htmlspecialchars($account['email']) ?></td>
                <td><?= htmlspecialchars($account['role']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No user accounts found.</p>
    <?php endif; ?>

</div>

</body>
</html>
