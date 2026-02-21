<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust path if needed
require_once '../config/database.php'; // Adjust path for your Database class

function sendAppointmentEmail($user_id, $date, $time, $reason, $appointment_id) {
    // Create a new Database instance
    $db = new Database();
    $conn = $db->getConnection();
    $conn->exec("SET time_zone = '+08:00'");

    // Use bindValue() instead of bind_param() for PDO
    $stmt = $conn->prepare("SELECT email, Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);  // Use bindValue() for PDO
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $user['email'];
    $userName = $user['Name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yoroleo10@gmail.com';
        $mail->Password = 'zgyy mflb rcyo kaeg'; // Consider using environment variables
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
        $mail->addAddress($email, $userName);
        $mail->Subject = 'Appointment Confirmation';
        
        // Correct order of values in the email body
        $mail->Body = "Hello $userName,\n\nYour appointment has been successfully scheduled.\n\nAppointment ID: $appointment_id\nDate: $date\nTime: $time\nReason: $reason\n\nThank you,\nJRU Clinic";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}

function sendRescheduleEmail($user_id, $appointment_id, $new_date, $new_time, $new_reason) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Fetch user details
    $stmt = $conn->prepare("SELECT email, Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $user['email'];
    $userName = $user['Name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yoroleo10@gmail.com';
        $mail->Password = 'zgyy mflb rcyo kaeg'; // Consider using environment variables
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
        $mail->addAddress($email, $userName);
        $mail->Subject = 'Appointment Rescheduled';
        $mail->Body = "Hello $userName,\n\nYour appointment has been successfully rescheduled.\n\nAppointment ID: $appointment_id\nNew Date: $new_date\nNew Time: $new_time\nNew Reason: $new_reason\n\nThank you,\nJRU Clinic";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}

function sendCancelEmail($user_id, $appointment_id, $date, $time, $reason) {
    $db = new Database();
    $conn = $db->getConnection();

    // Fetch user details
    $stmt = $conn->prepare("SELECT email, Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $user['email'];
    $userName = $user['Name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yoroleo10@gmail.com';
        $mail->Password = 'zgyy mflb rcyo kaeg'; // Consider using environment variables
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
        $mail->addAddress($email, $userName);
        $mail->Subject = 'Appointment Cancelled';
        $mail->Body = "Hello $userName,\n\nYour appointment has been successfully cancelled.\n\nAppointment ID: $appointment_id\nDate: $date\nTime: $time\nReason: $reason\n\nThank you,\nJRU Clinic";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}

function sendPrescriptionEmail($user_id, $symptoms, $first_aid, $remarks, $prescription) {
    // Create a new Database instance
    $db = new Database();
    $conn = $db->getConnection(); // Get the connection

    // Fetch user details (patient)
    $stmt = $conn->prepare("SELECT email, Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);  // Use bindValue() for PDO
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $user['email'];
    $userName = $user['Name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yoroleo10@gmail.com';
        $mail->Password = 'zgyy mflb rcyo kaeg'; // Consider using environment variables
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
        $mail->addAddress($email, $userName);
        $mail->Subject = 'Medical Prescription and Certificate';

        // Generate the email body
        $body = "Hello $userName,\n\nHere are your medical details from your recent check-up.\n\n";
        $body .= "Symptoms: $symptoms\nFirst Aid: $first_aid\nRemarks: $remarks\nPrescription: $prescription\n\n";
        $body .= "\n\nThank you,\nJRU Clinic";

        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}

function sendCertificateEmail($user_id, $certificate) {
    // Create a new Database instance
    $db = new Database();
    $conn = $db->getConnection(); // Get the connection

    // Fetch user details (patient)
    $stmt = $conn->prepare("SELECT email, Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);  // Use bindValue() for PDO
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $user['email'];
    $userName = $user['Name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yoroleo10@gmail.com';
        $mail->Password = 'zgyy mflb rcyo kaeg'; // Consider using environment variables
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
        $mail->addAddress($email, $userName);
        $mail->Subject = 'Medical Certificate';

        // Generate the email body for the certificate
        $body = "Hello $userName,\n\nPlease find your medical certificate below.\n\n";
        $body .= "$certificate";

        $body .= "\n\nThank you,\nJRU Clinic";

        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}
?>
