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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"];
    $table = $role === 'admin' ? 'admins' : 'staffs';

    $sql = "SELECT password_hash FROM $table WHERE username = ?";
    $stmt = sqlsrv_query($conn, $sql, array($username));
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && password_verify($entered_password, $user['password_hash'])) {
        $sql = "SELECT message_encrypted FROM messages WHERE id = ? AND receiver_username = ?";
        $stmt = sqlsrv_query($conn, $sql, array($message_id, $username));
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $message = base64_decode($row['message_encrypted']);
        } else {
            $error = "Message not found.";
        }
    } else {
        $error = "❌ Incorrect password.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Unmask Message</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        form {
            text-align: center;
        }

        input[type="password"] {
            width: 80%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        input[type="submit"] {
            margin-top: 15px;
            width: 50%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .message-display {
            white-space: pre-wrap;
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            color: #2c3e50;
        }

        .error {
            text-align: center;
            margin-top: 15px;
            color: #c0392b;
            font-weight: bold;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 25px;
        }

        .back-btn a {
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn a:hover {
            background-color: #626e70;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Unmask Message</h2>

    <?php if ($message): ?>
        <p><strong>Full Message:</strong></p>
        <div class="message-display"><?= nl2br(htmlspecialchars($message)) ?></div>
    <?php else: ?>
        <form method="POST">
            <label>Enter your password to unmask the message:</label><br><br>
            <input type="password" name="password" required><br>
            <input type="submit" value="Unmask">
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="back-btn">
        <a href="view_messages.php">⬅️ Back to Messages</a>
    </div>
</div>

</body>
</html>
