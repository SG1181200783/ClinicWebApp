<?php
session_start();
if ($_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
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
            background-color: #27ae60;
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
            background-color: #1e8449;
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
    <h2>Welcome, Staff</h2>

    <div class="section-title">🧑‍⚕️ Assigned Tasks</div>
    <div class="button-group">
        <a href="view_patients_staff.php" class="btn">View Patients</a>
    </div>

    <div class="section-title">📩 Messaging</div>
    <div class="button-group">
        <a href="send_message.php" class="btn">Send Message to Admin</a>
        <a href="view_messages.php" class="btn">View Messages</a>
    </div>

    <div class="section-title">🔒 Session</div>
    <div class="button-group">
        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>
</div>

</body>
</html>
