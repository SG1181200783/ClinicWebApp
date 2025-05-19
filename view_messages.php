<?php
session_start();
include("db.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

$sql = "SELECT * FROM messages WHERE receiver_username = ? ORDER BY timestamp DESC";
$stmt = sqlsrv_query($conn, $sql, array($username));
?>