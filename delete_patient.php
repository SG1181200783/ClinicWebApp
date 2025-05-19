<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include("db.php");
if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}
$id = $_GET['id'];
$sql = "DELETE FROM patients WHERE id = ?";
$stmt = sqlsrv_query($conn, $sql, array($id));
if ($stmt) {
    header("Location: view_patients_admin.php");
    exit;
} else {
    echo "❌ Error deleting patient.<br>" . print_r(sqlsrv_errors(), true);
}
?>