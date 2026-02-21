<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointment</title>
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

        .form-container {
            margin: 150px auto 20px auto;
            background-color: #f8f8ff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 85%;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1s ease-in-out forwards;
        }

        h2 {
            font-family: 'Futura', sans-serif;
            font-size: 28px;
            color: #0D2B66;
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #DC3545;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        button:hover {
            background-color: #c82333;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
        }

        button:active {
            transform: scale(0.95);
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            color: #0D2B66;
            text-decoration: none;
            font-weight: bold;
        }

        a.back-link:hover {
            text-decoration: underline;
            color: #ECAA05;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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
</div>

<!-- Cancel Form Section -->
<div class="form-container">
    <h2>Cancel Appointment</h2>
    <form action="../controller/AppointmentController.php" method="POST">
        <label for="appointment_id">Appointment ID:</label>
        <input type="text" name="appointment_id" id="appointment_id" required>
        <button type="submit" name="cancel_appointment">Cancel Appointment</button>
    </form>
    <a class="back-link" href="user_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
