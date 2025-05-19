<?php
session_start();

define('SESSION_TIMEOUT', 300); // 5 minutes

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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 700px;
            margin: 80px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }

        h2 {
            margin-bottom: 35px;
            color: #2c3e50;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-red {
            background-color: #e74c3c;
        }

        .btn-red:hover {
            background-color: #c0392b;
        }

        .section-title {
            font-size: 18px;
            color: #555;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, Admin</h2>

    <div class="section-title">🧑‍⚕️ Patient Management</div>
    <div class="button-group">
        <a href="add_patient.php" class="btn">Add Patient</a>
        <a href="view_patients_admin.php" class="btn">View Patients</a>
    </div>

    <div class="section-title">📩 Messaging</div>
    <div class="button-group">
        <a href="send_message.php" class="btn">Send Message to Staff</a>
        <a href="view_messages.php" class="btn">View Messages</a>
    </div>

    <div class="section-title">🔒 Session</div>
    <div class="button-group">
        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>
</div>

</body>
</html>
