<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'google-config.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);
        $oauth = new Google_Service_Oauth2($client);
        $userInfo = $oauth->userinfo->get();

        $email = $userInfo->email;
        $name = $userInfo->name;

        // DB connect
        $conn = new mysqli("sql305.infinityfree.com", "if0_38245583", "f0urtun3rs", "if0_38245583_clinic");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, role FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $role);
            $stmt->fetch();

            // Store session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Log it
            $ip = $_SERVER['REMOTE_ADDR'];
            $log_stmt = $conn->prepare("INSERT INTO system_log (account_id, role, ip_address) VALUES (?, ?, ?)");
            $log_stmt->bind_param("sss", $email, $role, $ip);
            $log_stmt->execute();

            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'doctor':
                    header("Location: staff_dashboard.php");
                    break;
                case 'user':
                    header("Location: user_dashboard.php");
                    break;
                default:
                    echo "Unauthorized role.";
            }
        } else {
            echo "Account not registered. Please contact admin.";
        }
    } else {
        echo "Token error: " . $token["error"];
    }
} else {
    echo "No code returned.";
}
