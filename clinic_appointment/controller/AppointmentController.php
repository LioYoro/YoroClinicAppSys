<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');
session_start();
require_once __DIR__ . '/../model/Appointment.php';
require_once __DIR__ . '/../model/email_helper.php';
require_once __DIR__ . '/../config/database.php';  // Include the Database class

$database = new Database();

// Get the database connection
$conn = $database->getConnection();

$conn->exec("SET time_zone = '+08:00'");

if (!isset($_SESSION["user"])) {
    header("Location: ../view/login.php");
    exit();
}

if (isset($_POST['create_appointment'])) {

        $appointment_date = $_POST['appointment_date'];
    $today = date('Y-m-d');

    // Rule 1: Prevent past date booking
    if ($appointment_date < $today) {
        echo "<script>alert('You cannot book an appointment for a past date.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    // Rule 2: Block if existing appointment that is not 'done'
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointment WHERE user_id = :user_id AND status = 'active'");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $existing = $stmt->fetchColumn();

    if ($existing > 0) {
        echo "<script>alert('You already have an active appointment. Please complete or cancel it before booking again.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $appointment = new Appointment();
    
    $appointment->setUserId($_SESSION['user']['id']);
    $appointment->setAppointmentDate($_POST['appointment_date']);
    $appointment->setAppointmentTime($_POST['appointment_time']);
    $appointment->setReason($_POST['reason']);

    // Create the appointment and get the appointment ID
    $appointment_id = $appointment->createAppointment(); // Assuming this function returns the appointment ID

    // Send the email with the correct Appointment ID
    sendAppointmentEmail($_SESSION['user']['id'], $_POST['appointment_date'], $_POST['appointment_time'], $_POST['reason'], $appointment_id);

    // Display a success message and redirect after 1 second
    echo "<script>alert('Appointment successfully scheduled! Appointment ID: $appointment_id');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../view/user_dashboard.php'; }, 1000);</script>";

    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reschedule_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['appointment_date'];
    $new_time = $_POST['appointment_time'];
    $new_reason = $_POST['reason'];

    // Check if the logged-in user is the owner of the appointment
    $user_id = $_SESSION['user']['id'];
    
    // Query to fetch the appointment details to verify it belongs to the logged-in user
    $stmt = $conn->prepare("SELECT * FROM appointment WHERE id = :appointment_id AND user_id = :user_id");
    $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        // Update the appointment details in the database
        $update_sql = "UPDATE appointment SET appointment_date = :appointment_date, appointment_time = :appointment_time, reason = :reason WHERE id = :appointment_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':appointment_date', $new_date, PDO::PARAM_STR);
        $update_stmt->bindParam(':appointment_time', $new_time, PDO::PARAM_STR);
        $update_stmt->bindParam(':reason', $new_reason, PDO::PARAM_STR);
        $update_stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $update_stmt->execute();

        // Send confirmation email
        sendRescheduleEmail($user_id, $appointment_id, $new_date, $new_time, $new_reason);

        // Redirect back to dashboard or confirmation page
        header("Location: ../view/user_dashboard.php");

        exit();
    } else {
        echo "Appointment not found or you do not have access to reschedule this appointment.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $user_id = $_SESSION['user']['id'];

    // Verify the appointment belongs to the user
    $stmt = $conn->prepare("SELECT * FROM appointment WHERE id = :appointment_id AND user_id = :user_id");
    $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        // Cancel the appointment (status only; or use DELETE if you prefer)
        $cancel_stmt = $conn->prepare("UPDATE appointment SET status = 'cancelled' WHERE id = :appointment_id");
        $cancel_stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $cancel_stmt->execute();

        // Send cancellation email
        sendCancelEmail($user_id, $appointment_id, $appointment['appointment_date'], $appointment['appointment_time'], $appointment['reason']);

        echo "<script>alert('Appointment cancelled successfully.');</script>";
        echo "<script>setTimeout(function() { window.location.href = '../view/user_dashboard.php'; }, 1000);</script>";
        exit();
    } else {
        echo "<script>alert('Appointment not found or does not belong to you.');</script>";
        echo "<script>setTimeout(function() { window.location.href = '../view/user_dashboard.php'; }, 1000);</script>";
        exit();
    }
}


?>
