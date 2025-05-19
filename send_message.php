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