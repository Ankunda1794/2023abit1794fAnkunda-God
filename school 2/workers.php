<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT position FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || strtolower($user['position']) !== 'headteacher') {
    echo "Access denied. Only the headteacher can access this page.";
    exit();
}

$result = $conn->query("SELECT 
    full_name, role, department, position,
    qualifications, class_teaching, contact, gender,
    staff_id, staff_type, address, subject, status, email, created_at
    FROM workers ORDER BY created_at DESC
");

$workers = [];
if ($result && $result->num_rows > 0) {
    $workers = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Workers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 40px 20px;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .table-container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table th {
            position: sticky;
            top: 0;
            background: #1e293b;
            color: #fff;
            z-index: 10;
        }
        h3 {
            margin-bottom: 30px;
            font-weight: 600;
            color: #1e293b;
        }
        .table td {
            vertical-align: middle;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container table-container">
    <h1>SCHOOL WORKERS</h1>

    <?php if (empty($workers)): ?>
        <div class="alert alert-warning">No workers found in the system.</div>
    <?php else: ?>
        <div class="table-responsive" style="max-height: 80vh;">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Qualifications</th>
                        <th>Class Teaching</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Staff ID</th>
                        <th>Staff Type</th>
                        <th>Address</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workers as $worker): ?>
                        <tr>
                            <td><?= htmlspecialchars($worker['full_name']) ?></td>
                            <td><?= htmlspecialchars($worker['role']) ?></td>
                            <td><?= htmlspecialchars($worker['department']) ?></td>
                            <td><?= htmlspecialchars($worker['position']) ?></td>
                            <td><?= htmlspecialchars($worker['qualifications']) ?></td>
                            <td><?= htmlspecialchars($worker['class_teaching']) ?></td>
                            <td><?= htmlspecialchars($worker['contact']) ?></td>
                            <td><?= htmlspecialchars($worker['gender']) ?></td>
                            <td><?= htmlspecialchars($worker['staff_id']) ?></td>
                            <td><?= htmlspecialchars($worker['staff_type']) ?></td>
                            <td><?= htmlspecialchars($worker['address']) ?></td>
                            <td><?= htmlspecialchars($worker['subject']) ?></td>
                            <td><?= htmlspecialchars($worker['status']) ?></td>
                            <td><?= htmlspecialchars($worker['email']) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($worker['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
