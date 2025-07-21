<?php
session_start();
require('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch timetable records
$query = "SELECT day, time, subject, class, teacher FROM timetable ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday'), time";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>School Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📅 General Timetable</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Day</th>
                <th>Time</th>
                <th>Subject</th>
                <th>Class</th>
                <th>Teacher</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['day']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= htmlspecialchars($row['teacher']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No timetable data available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
