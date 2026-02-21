<?php

require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Manila');


class AccountModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->conn->exec("SET time_zone = '+08:00'");
    }

    public function login($studentid, $password) {
        $query = "SELECT * FROM ACCOUNT WHERE id = :studentid AND password = SHA2(:password, 256)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":studentid", $studentid);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getUserByStudentID($studentid) {
        $query = "SELECT * FROM ACCOUNT WHERE id = :studentid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":studentid", $studentid);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updatePassword($studentid, $new_password) {
        $query = "UPDATE ACCOUNT SET password = SHA2(:password, 256) WHERE id = :studentid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $new_password);
        $stmt->bindParam(":studentid", $studentid);
        return $stmt->execute();
    }
}




?>
