<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Manila');


require_once __DIR__ . '/../config/database.php';

$db = new Database();
$pdo = $db->getConnection();
$pdo->exec("SET time_zone = '+08:00'");

$account = null;
$message = "";
$success = false;

// Step 1: Look for the ID in all 3 tables
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $account_id = trim($_POST["account_id"]);
    $tables = ["admin", "doctor", "user"];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT *, '$table' AS role_table FROM $table WHERE id = ?");
        $stmt->execute([$account_id]);
        if ($account = $stmt->fetch(PDO::FETCH_ASSOC)) {
            break;
        }
    }

    if (!$account) {
        $message = "Account not found in any table.";
    }
}

// Step 2: Update and possibly transfer
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $current_table = $_POST["current_table"];
    $new_role = $_POST["role"];
    $new_id = $_POST["new_id"];  // Add a field for the new ID

    // Check if any fields are empty
    if (empty($name) || empty($email) || empty($new_id)) {
        $message = "All fields (Name, Email, New ID) are required to be entered.";
    } else {
        
        $canProceed = true;

        if ($new_id !== $id) {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ? UNION SELECT * FROM doctor WHERE id = ? UNION SELECT * FROM user WHERE id = ?");
            $stmt->execute([$new_id, $new_id, $new_id]);
            if ($stmt->rowCount() > 0) {
                $message = "The new ID is already in use. Please choose a different ID.";
                $canProceed = false;
            }
        }

        // Check duplicate email
        if (!is_null($account) && $email !== $account["email"]) {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ? UNION SELECT * FROM doctor WHERE email = ? UNION SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email, $email, $email]);
            if ($stmt->rowCount() > 0) {
                $message = "The email is already in use. Please choose a different email.";
                $canProceed = false;
            }
        }
        if ($canProceed) {
    performUpdateOrTransfer();
}
    }
}

function performUpdateOrTransfer() {
    global $pdo, $id, $name, $email, $current_table, $new_role, $new_id, $message, $success;

    // Final email duplication check before executing any DB command
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ? UNION SELECT * FROM doctor WHERE email = ? UNION SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email, $email, $email]);
    $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($existing as $row) {
        if ($row["id"] != $id) {
            $message = "The email is already in use. Please choose a different email.";
            $success = false;
            return;
        }
    }

    if ($new_role === $current_table) {
        // Just update the existing account in the current table
        $stmt = $pdo->prepare("UPDATE $current_table SET id = ?, name = ?, email = ? WHERE id = ?");
        $success = $stmt->execute([$new_id, $name, $email, $id]);
        $message = "Account updated successfully.";
    } else {
        // Transfer to new table (including new ID)
        $pdo->beginTransaction();
        try {
            // Insert to new table (including new ID)
            $stmt = $pdo->prepare("INSERT INTO $new_role (id, name, email, password) SELECT ?, ?, ?, password FROM $current_table WHERE id = ?");
            $stmt->execute([$new_id, $name, $email, $id]);

            // Delete from old table
            $stmt = $pdo->prepare("DELETE FROM $current_table WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->commit();
            $success = true;
            $message = "Account moved to $new_role table with new ID: $new_id";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Transfer failed: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add inside the <head> -->
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        color: #0D2B66;
        margin: 0;
        padding: 0;
    }
    .header {
        background-color: #0D2B66;
        color: white;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header h1 {
        margin: 0;
    }
    .back-btn {
        background: #ECAA05;
        color: #0D2B66;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .back-btn:hover {
        background: #0D2B66;
        color: #ECAA05;
        transform: scale(1.05);
    }
    .container {
        background: white;
        padding: 30px;
        max-width: 600px;
        margin: 40px auto;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
    h2 {
        margin-top: 0;
        text-align: center;
    }
    input[type="text"], input[type="email"], select {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    button {
        padding: 12px 20px;
        background-color: #0D2B66;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    button:hover {
        background-color: #ECAA05;
        color: #0D2B66;
    }
    .message {
        margin-top: 10px;
        font-weight: bold;
        color: <?= $success ? "green" : "red" ?>;
    }
</style>

</head>
<body>

<div class="header">
    <h1>Update Account</h1>
    <a class="back-btn" href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Back</a>
</div>


<div class="container">
    <h2>Update Account</h2>

    <!-- Search Form -->
    <form method="POST">
        <label>Enter Account ID:</label>
        <input type="text" name="account_id" required pattern="[0-9\-]+" title="Only numbers and dashes are allowed" oninput="this.value = this.value.replace(/[^0-9\-]/g, '')">

        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($account): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($account["id"]) ?>">
            <input type="hidden" name="current_table" value="<?= htmlspecialchars($account["role_table"]) ?>">

            <label>New ID:</label>
            <input type="text" name="new_id" value="<?= htmlspecialchars($account["id"]) ?>" required pattern="[0-9\-]+" title="Only numbers and dashes are allowed">

            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($account["name"]) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($account["email"]) ?>" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin" <?= $account["role_table"] === "admin" ? "selected" : "" ?>>Admin</option>
                <option value="doctor" <?= $account["role_table"] === "doctor" ? "selected" : "" ?>>Doctor</option>
                <option value="user" <?= $account["role_table"] === "user" ? "selected" : "" ?>>User</option>
            </select>

            <button type="submit" name="update">Update Account</button>
        </form>
    <?php endif; ?>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
</div>

</body>
</html>
