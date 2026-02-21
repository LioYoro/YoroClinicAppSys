<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
session_start();

require_once __DIR__ . '/../model/AccountModel.php';

class AccountController {
    private $model;

    public function __construct() {
        $this->model = new AccountModel();
    }

    public function login() { 
        if ($_SERVER["REQUEST_METHOD"] == "POST") { 
            $studentid = $_POST["id"]; 
            $password = $_POST["password"];

            if (empty($studentid)) { 
                echo "<script>alert('Invalid ID!'); window.location.href='../view/login.php'; </script>"; 
                exit(); 
            } elseif (empty($password)) { 
                echo "<script>alert('Invalid Password!'); window.location.href='../view/login.php' </script>"; 
            exit(); 
            } 
        $user = $this->model->login($studentid, $password); 

        if ($user) { 
            $_SESSION["user"] = $user; 
            if ($user["role"] === "admin") { 
                header("Location: ../view/admin_dashboard.php"); 
            } elseif ($user["role"] === "student") { 
                header("Location: ../view/user_dashboard.php"); 
            } else { 
                header("Location: ../view/staff_dashboard.php"); 
            }  
        exit(); 
        } else { 
                echo "<script>alert('Invalid Credentials!'); window.location.href='../view/login.php'; </script>"; 
}
    } 
     }

    public function logout() {
        session_destroy();
        header("Location: ../view/login.php");
        exit();
    }

    public function forgotPassword() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["forgot_password"])) {
            $studentid = $_POST["id"];
            $user = $this->model->getUserByStudentID($studentid);
    
            if ($user) {
                $_SESSION["reset_studentid"] = $studentid;
                header("Location: ../view/reset_password.php");
                exit();
            } else {
                echo "<script>alert('Student ID not found!'); window.location.href='../view/forgot_password.php';</script>";
            }
        }
    }

    public function resetPassword() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
            $newPassword = $_POST["new_password"];
            $confirmPassword = $_POST["confirm_password"];
     
            if ($newPassword !== $confirmPassword) {
                echo "<script>alert('Passwords do not match!'); window.location.href='../view/reset_password.php';</script>";
                return;
            }
     
            $studentid = $_SESSION["reset_studentid"];
     
            // Update the password in the database
            $result = $this->model->updatePassword($studentid, $newPassword);
     
            if ($result) {
                echo "<script>alert('Password successfully updated!'); window.location.href='../view/login.php';</script>";
            } else {
                echo "<script>alert('Failed to update password!'); window.location.href='../view/reset_password.php';</script>";
            }
        }
    }
    

}



$controller = new AccountController();

if (isset($_POST["login"])) {
    $controller->login();
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $controller->logout();
}

if (isset($_POST["forgot_password"])) {
    $controller->forgotPassword();
}

if (isset($_POST["reset_password"])) {
    $controller->resetPassword();
}


if (isset($_POST['create_appointment'])) {
    $appointment = new Appointment();
    $appointment->setUserId($_SESSION['user']['id']);
    $appointment->setAppointmentDate($_POST['appointment_date']);
    $appointment->setAppointmentTime($_POST['appointment_time']);
    $appointment->setReason($_POST['reason']);

    $appointment->createAppointment();

    header("Location: ../view/user_dashboard.php");
    exit();
}


?>
