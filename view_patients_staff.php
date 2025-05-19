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

<!DOCTYPE html>
<html>
<head>
    <title>Assigned Patients</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #2ecc71;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .back-btn {
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #626e70;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Assigned Patients</h2>

    <table>
        <tr>
            <th>Full Name</th>
            <th>IC Number</th>
            <th>Phone</th>
            <th>Condition</th>
        </tr>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['ic_number']) ?></td>
                <td><?= htmlspecialchars($row['phone_number']) ?></td>
                <td><?= htmlspecialchars($row['medical_condition']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard_staff.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>

</body>
</html>

