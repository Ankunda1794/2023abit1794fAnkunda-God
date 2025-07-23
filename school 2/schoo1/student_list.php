<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students from users table
$stmt = $conn->prepare("
    SELECT 
        id, username, full_name, gender,
        class, dormitory, admission_no, registration_no,
        contact, address, email, created_at
    FROM users 
    WHERE role = 'student'
    ORDER BY created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            padding: 30px;
        }
        .table-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table th {
            background: #0d6efd;
            color: white;
        }
        .table td, .table th {
            font-size: 14px;
            vertical-align: middle;
        }
        h3 {
            margin-bottom: 25px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container table-container">
    <h3> Complete Student List</h3>

    <?php if (empty($students)): ?>
        <div class="alert alert-info">No students found in the system.</div>
    <?php else: ?>
        <div class="table-responsive" style="max-height: 75vh;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Class</th>
                        <th>dormitory</th>
                        <th>Admission No</th>
                        <th>Registration No</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Registered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['username']) ?></td>
                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                            <td><?= htmlspecialchars($student['gender']) ?></td>
                            <td><?= htmlspecialchars($student['class']) ?></td>
                            <td><?= htmlspecialchars($student['dormitory'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($student['admission_no'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($student['registration_no'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($student['contact']) ?></td>
                            <td><?= htmlspecialchars($student['address']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($student['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
