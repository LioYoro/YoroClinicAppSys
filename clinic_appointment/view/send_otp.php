<?php
session_start();
require '../config/database.php';
require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Database();
$pdo = $database->getConnection();

$userId = $_SESSION['otp_user_id'] ?? '';
if (empty($userId)) {
    die("User session not found.");
}

$stmt = $pdo->prepare("SELECT email FROM user WHERE id = ? 
                       UNION SELECT email FROM doctor WHERE id = ? 
                       UNION SELECT email FROM admin WHERE id = ?");
$stmt->execute([$userId, $userId, $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User ID not found.");
}

$email = $user['email'];

$otp = rand(100000, 999999);
$createdAt = date('Y-m-d H:i:s');
$expiresAt = date('Y-m-d H:i:s', strtotime($createdAt . ' +5 minutes'));

$stmt = $pdo->prepare("INSERT INTO otp_verification (user_id, otp, created_at, expires_at, status) 
                       VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$userId, $otp, $createdAt, $expiresAt]);

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yoroleo10@gmail.com';
    $mail->Password = 'zgyy mflb rcyo kaeg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('yoroleo10@gmail.com', 'JRU Clinic');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP for Login';
    $mail->Body = "Your OTP is: $otp. It is valid for 5 minutes.";

    $mail->send();
    header("Location: otp_verification.php");
    exit();
} catch (Exception $e) {
    die("Email sending failed: " . $mail->ErrorInfo);
}
?>