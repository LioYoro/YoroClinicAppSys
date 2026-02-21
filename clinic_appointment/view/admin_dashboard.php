<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        /* Header with Logout Button */
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

        .logout-btn {
            background: #ECAA05;
            color: #0D2B66;
            padding: 8px 16px;  /* Smaller size */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;  /* Smaller text */
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn:hover {
            background: #0D2B66;
            color: #ECAA05;
            transform: scale(1.05);
        }

        /* Content and Containers */
        .content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .box {
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 30px;
            flex: 1;
            min-width: 300px;
            transition: 0.3s;
            text-align: center;
            border-top: 6px solid #ECAA05;  /* Yellow accent */
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        h2, h3 {
            color: #0D2B66;
        }

        .box p {
            color: #555;
        }

        /* Button for redirecting to Login Activity */
        .view-activity-btn {
            background: #0D2B66;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            transition: 0.3s;
        }

        .view-activity-btn:hover {
            background: #ECAA05;
            color: #0D2B66;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .content {
                padding: 20px;
            }

            .logout-btn {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>JRU Online Clinic Appointment - Admin Dashboard</h1>
    <a href="../controller/AccountController.php?logout=true" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="admin-container">
        <!-- Updated System Log Box with Login Activity Button -->
        <div class="box">
            <h2>System Log</h2>
            <p>Review and analyze recent activity logs.</p>
            <a href="admin_activity_log.php" class="view-activity-btn">
                View Login Activity
            </a>
        </div>

        <div class="box">
        <h2>All Accounts</h2>
        <p>View and manage all the accounts registered in the system.</p>
        <a href="view_all_accounts.php" class="view-activity-btn">
            View All Accounts
        </a>
    </div>
    
    <div class="box">
    <h2>Update Account</h2>
    <p>Search and edit any account, including role transfers.</p>
    <a href="update_account.php" class="view-activity-btn">
        Update Account
    </a>
</div>


    </div>
</div>

</body>
</html>
