<?php
session_start();
require '../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

// Ensure user ID is stored in session
if (!isset($_SESSION['otp_user_id'])) {
    die("Unauthorized access.");
}

$userId = (string) $_SESSION['otp_user_id']; // Ensure user_id is treated as a string
$enteredOtp = trim($_POST['otp'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the OTP exists for the given user
    $stmt = $pdo->prepare("SELECT * FROM otp_verification WHERE user_id = ? AND status != 'verified' ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otpData) {
        $error = "No OTP record found for this user.";
    } elseif ($otpData['status'] === 'verified') {
        $error = "This OTP has already been used.";
    } elseif ($otpData['expires_at'] <= date('Y-m-d H:i:s')) {
        $error = "This OTP has expired. Please request a new one.";
    if ((string) $otpData['otp'] !== (string) $enteredOtp) {
    $error = "Incorrect OTP. Please try again.";
    }
    } else {
        // OTP is valid, mark it as verified
        $updateStmt = $pdo->prepare("UPDATE otp_verification SET status = 'verified' WHERE id = ?");
        $updateStmt->execute([$otpData['id']]);

        // Retrieve user details from the correct table
        $stmt = $pdo->prepare("
            SELECT id, name, email, 'user' AS role FROM user WHERE id = ?
            UNION 
            SELECT id, name, email, 'doctor' AS role FROM doctor WHERE id = ?
            UNION 
            SELECT id, name, email, 'admin' AS role FROM admin WHERE id = ?");
        $stmt->execute([$userId, $userId, $userId]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Store user details in session
            $_SESSION["user"] = $userData;

            // Redirect to the correct dashboard
            switch ($userData["role"]) {
                case "admin":
                    header("Location: admin_dashboard.php");
                    break;
                case "doctor":
                    header("Location: staff_dashboard.php");
                    break;
                default:
                    header("Location: user_dashboard.php");
                    break;
            }
            exit();
        } else {
            $error = "User details not found after OTP verification.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            font-family: 'Futura', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('background.jpg') no-repeat center center/cover;
            color: #333;
            transition: background 0.5s ease;
        }
        form {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            text-align: center;
            max-width: 420px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }
        h2 {
            font-family: 'Futura', sans-serif;
            font-size: 28px;
            margin-bottom: 20px;
            color: #007BFF;
            transition: color 0.3s;
        }
        h2:hover {
            color: #0056b3;
        }
        input {
            width: 100%;
            padding: 14px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }
        button:hover {
            background: #218838;
            transform: scale(1.05);
        }
        button:active {
            transform: scale(0.98);
        }
        p {
            color: red;
            margin-top: 10px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.5s;
        }
        p.visible {
            opacity: 1;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>OTP Verification</h2>
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify OTP</button>

        <?php if (!empty($error)): ?>
            <p class="visible"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>
</body>
</html>