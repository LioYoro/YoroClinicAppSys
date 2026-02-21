<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/database.php';

require_once 'google-config.php';
$login_url = $client->createAuthUrl();

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

$errorMessage = "";
$max_attempts = 5;
$lockout_time = 10 * 60; // 10 minutes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    if (!isset($_SESSION['login_attempts'][$id])) {
        $_SESSION['login_attempts'][$id] = ['count' => 0, 'timestamp' => time()];
    }

    $time_since_last_attempt = time() - $_SESSION['login_attempts'][$id]['timestamp'];

    if ($_SESSION['login_attempts'][$id]['count'] >= $max_attempts && $time_since_last_attempt < $lockout_time) {
        $time_left = ceil(($lockout_time - $time_since_last_attempt) / 60);
        $errorMessage = "Too many failed attempts. Try again in $time_left minutes.";
    } else {
        if ($time_since_last_attempt >= $lockout_time) {
            $_SESSION['login_attempts'][$id]['count'] = 0;
        }

        $tables = ['user', 'doctor', 'admin'];
        $user = null;
        $tableMatched = null;

        foreach ($tables as $tbl) {
            $stmt = $pdo->prepare("SELECT * FROM $tbl WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data && hash_equals($data['password'], hash('sha256', $password))) {
                $user = $data;
                $tableMatched = $tbl;
                break;
            }
        }

        if ($user) {
            $_SESSION['login_attempts'][$id] = ['count' => 0, 'timestamp' => time()];
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => strtolower($tableMatched)
            ];
            $_SESSION['otp_user_id'] = $user['id'];

            // Record login
            recordLogin($pdo, $user['id'], strtolower($tableMatched));

            header("Location: send_otp.php");
            exit();
        } else {
            $_SESSION['login_attempts'][$id]['count']++;
            $_SESSION['login_attempts'][$id]['timestamp'] = time();
            $remaining = $max_attempts - $_SESSION['login_attempts'][$id]['count'];

            $errorMessage = $remaining > 0
                ? "Invalid credentials. You have $remaining attempts left."
                : "Too many failed attempts. Try again in 10 minutes.";
        }
    }
}

function recordLogin($pdo, $account_id, $role) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $pdo->prepare("INSERT INTO system_log (account_id, role, ip_address, login_time) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$account_id, $role, $ip]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login_design.css">
</head>
<body>
    <p class="welcome-msg">Welcome to JRU Online Clinic Appointment!</p>
    <div class="login-container">
        <h2>Log in to JRU - Clinic</h2>
        <form method="POST" action="">
            <input type="text" name="id" placeholder="Student ID" autocomplete="off" required><br>
            <input type="password" name="password" placeholder="Password" autocomplete="off" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (!empty($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <a href="<?= htmlspecialchars($login_url) ?>">Login with Google</a>

        <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
    </div>
</body>
</html>