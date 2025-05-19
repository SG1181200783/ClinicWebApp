<?php

$serverName = "LAPTOP-O949GDTL\\MSSQLSERVERDEV";
$connectionOptions = [
    "Database" => "ClinicDB",
    "UID" => $_SESSION['username'],
    "PWD" => $_SESSION['password']
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("❌ DB connection failed.<br>" . print_r(sqlsrv_errors(), true));
}
?>