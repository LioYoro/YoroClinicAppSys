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
    <title>Reschedule Appointment</title>
    <link rel="stylesheet" href="login_design.css">
</head>
<body>
    <h2>Reschedule Appointment</h2>
    <form action="../controller/AppointmentController.php" method="POST">
        <label for="appointment_id">Appointment ID:</label>
        <input type="text" name="appointment_id" required><br><br>

        <label for="appointment_date">New Date:</label>
        <input type="date" name="appointment_date" required><br><br>

        <label for="appointment_time">New Time:</label>
        <input type="time" name="appointment_time" required><br><br>

        <label for="reason">Reason:</label>
        <textarea name="reason" rows="4" cols="30" required></textarea><br><br>

        <input type="submit" name="reschedule_appointment" value="Reschedule Appointment">
    </form>
</body>
</html>
