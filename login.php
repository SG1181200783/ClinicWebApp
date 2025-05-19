<?php
session_start();
$error = "";
$ip_address = $_SERVER['REMOTE_ADDR'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $logConn = sqlsrv_connect("LAPTOP-O949GDTL\\MSSQLSERVERDEV", [
        "Database" => "ClinicDB",
        "UID" => "admin_user",
        "PWD" => "Admin@123"
    ]);

    if ($logConn) {
        $checkSql = "
            SELECT COUNT(*) AS failed_attempts
            FROM login_attempts
            WHERE username = ? AND ip_address = ? AND successful = 0
              AND attempt_time > DATEADD(MINUTE, -5, GETDATE())";
        $checkStmt = sqlsrv_query($logConn, $checkSql, [$username, $ip_address]);
        $row = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);

        if ($row && $row['failed_attempts'] >= 3) {
            $error = "❌ Account temporarily locked due to multiple failed login attempts. Try again in 5 minutes.";
        } else {
            $conn = sqlsrv_connect("LAPTOP-O949GDTL\\MSSQLSERVERDEV", [
                "Database" => "ClinicDB",
                "UID" => $username,
                "PWD" => $password
            ]);

            if ($conn) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;

                $logSql = "EXEC log_login_attempt @username = ?, @ip_address = ?, @successful = ?";
                sqlsrv_query($conn, $logSql, [$username, $ip_address, 1]);

                $_SESSION['role'] = ($username === "admin_user") ? "admin" : "staff";
                header("Location: " . ($_SESSION['role'] === 'admin' ? "dashboard_admin.php" : "dashboard_staff.php"));
                exit;
            } else {
                $error = "❌ Login failed. Check credentials.";
                $logSql = "EXEC log_login_attempt @username = ?, @ip_address = ?, @successful = ?";
                sqlsrv_query($logConn, $logSql, [$username, $ip_address, 0]);
            }
        }
    } else {
        $error = "❌ Internal error connecting to logging system.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clinic Login</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-box {
            width: 400px;
            margin: 80px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #444;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .timeout-message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Clinic Login</h2>

    <?php
    if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
        echo "<p class='timeout-message'>⏳ Session expired due to inactivity. Please log in again.</p>";
    }
    ?>

    <form method="POST">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Login">
        </div>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
</div>

</body>
</html>
