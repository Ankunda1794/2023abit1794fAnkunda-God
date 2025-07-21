<?php
session_start();
require 'config.php';

// ✅ Ensure only logged-in students can access this page
if (
    !isset($_SESSION['uid'], $_SESSION['role'], $_SESSION['class']) ||
    $_SESSION['role'] !== 'student'
) {
    die("Access denied. You must be logged in as a student.");
}

$student_class = $_SESSION['class'];

// ✅ Fetch assignments for the student's class using `uploaded_at`
$stmt = $conn->prepare("SELECT id, title, subject, file_path, uploaded_at FROM assignments WHERE class = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("s", $student_class);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .container { margin-top: 40px; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body class="container">
    <h2 class="mb-4 text-center">📘 Assignments for Class <?= htmlspecialchars($student_class) ?></h2>

    <?php if (empty($assignments)): ?>
        <div class="alert alert-info text-center">No assignments uploaded yet for your class.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Title</th>
                    <th>Date Uploaded</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $index => $a): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($a['subject']) ?></td>
                        <td><?= htmlspecialchars($a['title']) ?></td>
                        <td><?= date('d M Y', strtotime($a['uploaded_at'])) ?></td>
                        <td>
                            <?php
                                $safe_path = htmlspecialchars($a['file_path']);
                                if (!empty($a['file_path']) && file_exists($a['file_path'])):
                            ?>
                                <a href="<?= $safe_path ?>" target="_blank" class="btn btn-sm btn-success">Download</a>
                            <?php else: ?>
                                <span class="text-danger">No file</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="student_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</body>
</html>
