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

<!DOCTYPE html>
<html>
<head>
    <title>View Messages</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #8e44ad;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .btn-unmask {
            background-color: #3498db;
            color: white;
            padding: 6px 14px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
            display: inline-block;
        }

        .btn-unmask:hover {
            background-color: #2980b9;
        }

        .back-btn {
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #626e70;
        }

        .back-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body></body>