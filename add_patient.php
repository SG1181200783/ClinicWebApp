<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["full_name"];
    $ic = $_POST["ic_number"];
    $phone = $_POST["phone_number"];
    $condition = $_POST["medical_condition"];
    $sql = "INSERT INTO patients (full_name, ic_number, phone_number, medical_condition) VALUES (?, ?, ?, ?)";
    $stmt = sqlsrv_query($conn, $sql, array($name, $ic, $phone, $condition));
    echo $stmt ? "✅ Patient added." : "❌ Failed to add patient.<br>" . print_r(sqlsrv_errors(), true);
}
?>
<form method="POST">
    Full Name: <input type="text" name="full_name" required><br>
    IC Number: <input type="text" name="ic_number" required><br>
    Phone: <input type="text" name="phone_number" required><br>
    Condition: <input type="text" name="medical_condition" required><br>
    <input type="submit" value="Add">
</form>