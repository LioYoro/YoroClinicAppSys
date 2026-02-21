<?php
require_once __DIR__ . '/../config/database.php';

class Appointment {
    private $conn;
    public $user_id;
    public $appointment_date;
    public $appointment_time;
    public $reason;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->conn->exec("SET time_zone = '+08:00'");
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setAppointmentDate($date) {
        $this->appointment_date = $date;
    }

    public function setAppointmentTime($time) {
        $this->appointment_time = $time;
    }

    public function setReason($reason) {
        $this->reason = $reason;
    }

    public function createAppointment() {
    $sql = "INSERT INTO appointment (user_id, appointment_date, appointment_time, reason, status)
            VALUES (:user_id, :appointment_date, :appointment_time, :reason, 'active')";
    
    $stmt = $this->conn->prepare($sql);

    // Use bindParam for PDO
    $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
    $stmt->bindParam(':appointment_date', $this->appointment_date, PDO::PARAM_STR);
    $stmt->bindParam(':appointment_time', $this->appointment_time, PDO::PARAM_STR);
    $stmt->bindParam(':reason', $this->reason, PDO::PARAM_STR);

    $stmt->execute();

    // Retrieve and return the Appointment ID (last inserted ID)
    return $this->conn->lastInsertId();  // This will return the ID of the last inserted appointment
}

}
?>
