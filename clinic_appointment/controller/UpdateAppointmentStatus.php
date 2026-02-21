<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

date_default_timezone_set('Asia/Manila');

$db = new Database();
$pdo = $db->getConnection();

// Check if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the appointment ID and the new status from the POST request
        $appointmentId = $_POST['appointment_id'];
        $newStatus = $_POST['status'];

        // Debugging: Output posted data to check if it's correct
        //var_dump($_POST); // Uncomment this line to see the POST data in the browser

        if (!in_array($newStatus, ['active', 'done'])) {
        echo "Invalid status!";
        exit;
    }

        // Prepare the SQL statement to update the status of the appointment
        $stmt = $pdo->prepare("UPDATE appointment SET status = ? WHERE id = ?");
        
        // Execute the query
        $executeResult = $stmt->execute([$newStatus, $appointmentId]);

        // Check if the query was successful
        if ($executeResult) {
            // Redirect to the dashboard page if the update is successful
            header("Location: ../view/staff_dashboard.php");
            exit();
        } else {
            echo "Error: Appointment status update failed!";
        }

    } catch (PDOException $e) {
        // If there is a database error, catch and display it
        echo "Database Error: " . $e->getMessage();
    }
}
?>
