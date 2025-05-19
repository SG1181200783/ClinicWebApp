<?php
session_start();

//  SESSION TIMEOUT: 5 minutes
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

$sql = "SELECT * FROM patients";
$stmt = sqlsrv_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Patients</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
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
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 6px 14px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            margin-right: 6px;
        }

        .edit-btn {
            background-color: #2ecc71;
        }

        .edit-btn:hover {
            background-color: #27ae60;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .back-btn {
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #626e70;
        }
    </style>
</head>
<body>
