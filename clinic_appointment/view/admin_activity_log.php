<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php'; 

// Set the time zone to ensure accurate date comparisons
date_default_timezone_set('Asia/Manila');  // Adjust to your local time zone

$db = new Database();        // Create instance of your class
$pdo = $db->getConnection();  // Make sure to include your database configuration file
$pdo->exec("SET time_zone = '+08:00'");

// Pagination Logic
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get the current page number from the URL, default to 1
$offset = ($page - 1) * $limit;  // Calculate the offset for the query

// Query to fetch login activity data with account name, limit, and offset
$sql = "SELECT sl.*, 
            COALESCE(u.name, d.name, a.name) AS account_name
        FROM system_log sl
        LEFT JOIN user u ON sl.account_id = u.id
        LEFT JOIN doctor d ON sl.account_id = d.id
        LEFT JOIN admin a ON sl.account_id = a.id
        ORDER BY sl.login_time DESC
        LIMIT :limit OFFSET :offset";


$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to count the total number of records for pagination calculation
$countQuery = "SELECT COUNT(*) AS total FROM system_log";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute();
$countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $countResult['total'];
$totalPages = ceil($totalRecords / $limit);  // Calculate the total number of pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Activity - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #0D2B66;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #0D2B66;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .back-btn {
            background: #ECAA05;
            color: #0D2B66;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn:hover {
            background: #0D2B66;
            color: #ECAA05;
            transform: scale(1.05);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0D2B66;
            color: white;
        }

        td {
            background-color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #0D2B66;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .pagination a:hover {
            background-color: #ECAA05;
            color: #0D2B66;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .back-btn {
                margin-top: 10px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>Login Activity - Admin Dashboard</h1>
    <a href="admin_dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Main Content -->
<div class="content" style="padding: 20px;">
    <h2>Login Activity Log</h2>

    <?php if (count($results) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Role</th>
                <th>IP Address</th>
                <th>Login Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                <td><?php echo htmlspecialchars($row['login_time']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No login activity recorded.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
