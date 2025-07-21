<?php
session_start();
require 'config.php';

// Check if logged in and is a teacher
if (!isset($_SESSION['role'], $_SESSION['position'], $_SESSION['class_teaching']) || $_SESSION['role'] !== 'staff' || $_SESSION['position'] !== 'teacher') {
    die("Access denied. Only teachers can view submissions.");
}

$teacher_class = $_SESSION['class_teaching'];
$teacher_username = $_SESSION['username'];

$subject_filter = '';
$submissions = [];
$error = '';

// Handle subject filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'])) {
    $subject_filter = trim($_POST['subject']);

    if (empty($subject_filter)) {
        $error = "Please enter a subject to filter.";
    } else {
        // Prepare query to fetch submissions for the teacher's class and subject
        $stmt = $conn->prepare("
            SELECT 
                s.id AS submission_id,
                u.full_name AS student_name,
                s.subject,
                s.file_path,
                s.submitted_at,
                a.title AS assignment_title
            FROM assignment_submissions s
            JOIN users u ON s.student_id = u.id
            JOIN assignments a ON s.assignment_id = a.id
            WHERE u.class = ? AND s.subject = ? AND a.class = ? 
            ORDER BY s.submitted_at DESC
        ");
        $stmt->bind_param("sss", $teacher_class, $subject_filter, $teacher_class);
        $stmt->execute();
        $result = $stmt->get_result();
        $submissions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($submissions)) {
            $error = "No submissions found for class '{$teacher_class}' and subject '{$subject_filter}'.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Assignment Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>View Assignment Submissions for Class: <?= htmlspecialchars($teacher_class) ?></h2>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="subject" placeholder="Enter subject to filter e.g. Mathematics" value="<?= htmlspecialchars($subject_filter) ?>" class="form-control" required>
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($submissions): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Assignment Title</th>
                    <th>Subject</th>
                    <th>Submitted At</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><?= htmlspecialchars($sub['student_name']) ?></td>
                        <td><?= htmlspecialchars($sub['assignment_title']) ?></td>
                        <td><?= htmlspecialchars($sub['subject']) ?></td>
                        <td><?= htmlspecialchars($sub['submitted_at']) ?></td>
                        <td><a href="<?= htmlspecialchars($sub['file_path']) ?>" download class="btn btn-sm btn-success">Download</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (!$error && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-info">No submissions found.</div>
    <?php endif; ?>

    <a href="staff_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>
