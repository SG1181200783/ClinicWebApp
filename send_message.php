<?php
session_start();
include("db.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$sender = $_SESSION['username'];
$role = $_SESSION['role'];
$error = "";
$success = "";

// Determine the fixed receiver
$receiver = $role === 'admin' ? 'staff_user' : 'admin_user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];
    $encoded_message = base64_encode($message);

    $sql = "INSERT INTO messages (sender_username, receiver_username, message_encrypted) VALUES (?, ?, ?)";
    $stmt = sqlsrv_query($conn, $sql, array($sender, $receiver, $encoded_message));

    if ($stmt) {
        $success = "✅ Message sent!";
    } else {
        $error = "❌ Failed to send message.<br>" . print_r(sqlsrv_errors(), true);
    }
}