<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role'], $_SESSION['class']) || $_SESSION['role'] !== 'student') {
    die("Access denied. Only students can view notes.");
}

$student_class = $_SESSION['class'];

$stmt = $conn->prepare("SELECT * FROM notes WHERE class = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("s", $student_class);
$stmt->execute();
$result = $stmt->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Class Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Notes for Class <?= htmlspecialchars($student_class) ?></h2>

    <?php if (empty($notes)): ?>
        <div class="alert alert-info">No notes available.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Uploaded At</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?= htmlspecialchars($note['title']) ?></td>
                        <td><?= htmlspecialchars($note['subject']) ?></td>
                        <td><?= htmlspecialchars($note['description']) ?></td>
                        <td><?= htmlspecialchars($note['uploaded_at']) ?></td>
                        <td><a href="<?= htmlspecialchars($note['file_path']) ?>" target="_blank">Download</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
