<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ⏳ SESSION TIMEOUT: 5 minutes
define('SESSION_TIMEOUT', 300);

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include("db.php");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["full_name"];
    $ic = $_POST["ic_number"];
    $phone = $_POST["phone_number"];
    $condition = $_POST["medical_condition"];

    $sql = "INSERT INTO patients (full_name, ic_number, phone_number, medical_condition) VALUES (?, ?, ?, ?)";
    $stmt = sqlsrv_query($conn, $sql, array($name, $ic, $phone, $condition));

    $message = $stmt ? "✅ Patient added successfully." : "❌ Failed to add patient.<br>" . print_r(sqlsrv_errors(), true);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 80px auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        input[type="submit"], .btn-back {
            width: 100%;
            padding: 12px;
            background-color: #2ecc71;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 15px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        .btn-back {
            background-color: #3498db;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: #c0392b;
        }

        .success {
            color: #27ae60;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Patient</h2>

    <form method="POST">
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="full_name" required>
        </div>

        <div class="form-group">
            <label>IC Number:</label>
            <input type="text" name="ic_number" required>
        </div>

        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" name="phone_number" required>
        </div>

        <div class="form-group">
            <label>Medical Condition:</label>
            <input type="text" name="medical_condition" required>
        </div>

        <input type="submit" value="Add Patient">
    </form>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : '' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <a href="dashboard_admin.php" class="btn-back">⬅️ Back to Dashboard</a>
</div>

</body>
</html>

