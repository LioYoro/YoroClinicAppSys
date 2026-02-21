<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../model/email_helper.php';
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Manila');

$db = new Database();
$conn = $db->getConnection();
$conn->exec("SET time_zone = '+08:00'");

// Start the session and check if doctor is logged in
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user'];  // Assuming doctor ID is stored in 'user' session variable

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch data from the form (check if data is set to avoid undefined index warnings)
    $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';
    $first_aid = isset($_POST['first_aid']) ? $_POST['first_aid'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $prescription = isset($_POST['prescription']) ? $_POST['prescription'] : '';
    $generate_certificate = isset($_POST['generate_certificate']) ? $_POST['generate_certificate'] : false;
    $certificate = '';

    // Get the appointment ID from POST
    $appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null;

    // Ensure that appointment_id is provided
    if (!$appointment_id) {
        exit("No appointment ID provided.");
    }

    // Ensure that all check-up fields are filled in by the doctor
    if (empty($symptoms) || empty($first_aid) || empty($remarks) || empty($prescription)) {
        exit("Please fill in all the fields before submitting the check-up.");
    }

    // Get the user (patient) for the appointment
    $stmt = $conn->prepare("SELECT user_id FROM appointment WHERE id = :appointment_id");
    $stmt->bindValue(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        exit("Appointment not found.");
    }

    $user_id = $appointment['user_id'];

    // Fetch user name for the certificate
    $stmt = $conn->prepare("SELECT Name FROM user WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userName = $user['Name'];

    // Save the check-up data to the medical_record table
    $stmt = $conn->prepare("INSERT INTO medical_record (appointment_id, user_id, doctor_id, symptoms, first_aid, remarks, prescription, certificate) 
        VALUES (:appointment_id, :user_id, :doctor_id, :symptoms, :first_aid, :remarks, :prescription, :certificate)");

    // If generate_certificate is checked, create the certificate content
    if ($generate_certificate) {
        $certificate = "Medical Certificate:\n\n";
        $certificate .= "This is to certify that the patient, $userName, has been examined on the date of this check-up.\n\n";
        $certificate .= "Symptoms observed: $symptoms\n";
        $certificate .= "First Aid administered: $first_aid\n";
        $certificate .= "Remarks: $remarks\n\n";
        $certificate .= "Prescription: $prescription\n\n";
        $certificate .= "It is advised that the patient take adequate rest and limit physical activity to promote recovery.\n";
        $certificate .= "Please follow the prescribed medication and consult with the doctor if symptoms persist.\n\n";
        $certificate .= "Thank you for trusting JRU Clinic.\n\n";
        sendCertificateEmail($user_id, $certificate);
    }

    // Bind values
    $stmt->bindValue(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->bindValue(':symptoms', $symptoms, PDO::PARAM_STR);
    $stmt->bindValue(':first_aid', $first_aid, PDO::PARAM_STR);
    $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
    $stmt->bindValue(':prescription', $prescription, PDO::PARAM_STR);
    $stmt->bindValue(':certificate', $certificate, PDO::PARAM_STR);
    $stmt->execute();

    // Send the prescription email to the patient
    sendPrescriptionEmail($user_id, $symptoms, $first_aid, $remarks, $prescription);

    // Update the appointment status to 'done'
    $stmt = $conn->prepare("UPDATE appointment SET status = 'done' WHERE id = :appointment_id");
    $stmt->bindValue(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to dashboard
    header("Location: staff_dashboard.php");
    exit();
} else {
    // Invalid request
    exit("Form submission failed.");
}
?>
