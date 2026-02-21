<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Manila');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link rel="stylesheet" href="login_design.css"> <!-- Assuming this is your external stylesheet -->
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
            margin: 120px auto;
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

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .form-container h2 {
            font-family: 'Futura', sans-serif;
            font-size: 28px;
            color: #0D2B66;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            text-align: left;
        }

        .form-container input[type="date"],
        .form-container input[type="time"],
        .form-container textarea,
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container input[type="submit"] {
            background-color: #0D2B66;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
        }

        .form-container input[type="submit"]:hover {
            background-color: #ECAA05;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(236, 170, 5, 0.7);
        }

        .form-container input[type="submit"]:active {
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

<!-- Create Appointment Form -->
<div class="form-container">
    <h2>Create Appointment</h2>
    <form action="../controller/AppointmentController.php" method="POST">
        <label for="appointment_date">Date:</label>
        <input type="date" name="appointment_date" required><br>

        <label for="appointment_time">Time:</label>
        <input type="time" name="appointment_time" required><br>

        <label for="reason">Reason:</label>
        <textarea name="reason" rows="4" cols="30" required></textarea><br>

        <input type="submit" name="create_appointment" value="Book Appointment">
    </form>
</div>

</body>
</html>
