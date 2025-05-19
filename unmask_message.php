<?php
session_start();
include("db.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$error = "";
$message = "";

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}
$message_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"];
    $table = $role === 'admin' ? 'admins' : 'staffs';

    $sql = "SELECT password_hash FROM $table WHERE username = ?";
    $stmt = sqlsrv_query($conn, $sql, array($username));
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && password_verify($entered_password, $user['password_hash'])) {
        $sql = "SELECT message_encrypted FROM messages WHERE id = ? AND receiver_username = ?";
        $stmt = sqlsrv_query($conn, $sql, array($message_id, $username));
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $message = base64_decode($row['message_encrypted']);
        } else {
            $error = "Message not found.";
        }
    } else {
        $error = "❌ Incorrect password.";
    }
}