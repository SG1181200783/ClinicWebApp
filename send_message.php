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

// Determine the fixed receiver
$receiver = $role === 'admin' ? 'staff_user' : 'admin_user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];
    $encoded_message = base64_encode($message);

    $sql = "INSERT INTO messages (sender_username, receiver_username, message_encrypted) VALUES (?, ?, ?)";
    $stmt = sqlsrv_query($conn, $sql, array($sender, $receiver, $encoded_message));

    if ($stmt) {
        $success = "✅ Message sent!";
    } else {
        $error = "❌ Failed to send message.<br>" . print_r(sqlsrv_errors(), true);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Send Message</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
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

        label {
            font-weight: bold;
            color: #333;
        }

        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            resize: vertical;
            margin-top: 6px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .feedback {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .success {
            color: #27ae60;
        }

        .error {
            color: #c0392b;
        }

        .back-btn {
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 25px;
        }

        .back-btn:hover {
            background-color: #626e70;
        }

        .back-container {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Send Message to <?php echo htmlspecialchars($receiver); ?></h2>

    <form method="POST">
        <label for="message">Message:</label><br>
        <textarea name="message" rows="6" required></textarea><br>
        <input type="submit" value="Send Message">
    </form>

    <?php if (!empty($success)): ?>
        <div class="feedback success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="feedback error"><?= $error ?></div>
    <?php endif; ?>

    <div class="back-container">
        <a href="dashboard_<?php echo $role; ?>.php" class="back-btn">⬅️ Back to Dashboard</a>
    </div>
</div>

</body>
</html>


