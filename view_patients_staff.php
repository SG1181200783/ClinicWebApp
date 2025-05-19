<?php
session_start();
if ($_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

include("db.php");
$sql = "SELECT full_name, ic_number, phone_number, medical_condition FROM patients";
$stmt = sqlsrv_query($conn, $sql);
?>