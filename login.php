<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Attempt connection with entered credentials
    $serverName = "LAPTOP-O949GDTL\\MSSQLSERVERDEV";
    $connectionOptions = [
        "Database" => "ClinicDB",
        "UID" => $username,
        "PWD" => $password
    ];
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn) {
        // ✅ Successful login
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        // Log successful login
        $logSql = "EXEC log_login_attempt @username = ?, @ip_address = ?, @successful = ?";
        $logParams = array($username, $ip_address, 1);
        sqlsrv_query($conn, $logSql, $logParams);

        // Redirect based on role
        if ($username === "admin_user") {
            $_SESSION['role'] = "admin";
            header("Location: dashboard_admin.php");
        } else {
            $_SESSION['role'] = "staff";
            header("Location: dashboard_staff.php");
        }
        exit;
    } else {
        // ❌ Failed login
        $error = "❌ Login failed. Check credentials.";

        // Use fallback connection to log failed attempt
        $logConn = sqlsrv_connect("LAPTOP-O949GDTL\\MSSQLSERVERDEV", [
            "Database" => "ClinicDB",
            "UID" => "admin_user",         // Make sure this login exists
            "PWD" => "Admin@123"           // Replace with your actual admin_user password
        ]);

        if ($logConn) {
            $logSql = "EXEC log_login_attempt @username = ?, @ip_address = ?, @successful = ?";
            $logParams = array($username, $ip_address, 0);
            $stmt = sqlsrv_query($logConn, $logSql, $logParams);
            if (!$stmt) {
                error_log("❌ Log insert failed: " . print_r(sqlsrv_errors(), true), 0);
            }
        } else {
            error_log("❌ logConn connection failed: " . print_r(sqlsrv_errors(), true), 0);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Clinic Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
