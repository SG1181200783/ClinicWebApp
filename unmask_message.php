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