<?php
session_start();
require 'config.php';

// Only teachers allowed
if (!isset($_SESSION['role'], $_SESSION['position'], $_SESSION['class_teaching']) || $_SESSION['role'] !== 'staff' || $_SESSION['position'] !== 'teacher') {
    die("Access denied. Only teachers can upload notes.");
}

$teacher_id = $_SESSION['uid'];
$teacher_class = $_SESSION['class_teaching'];
$error = '';
$success = '';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $subject = $_POST['subject'] ?? '';

    if (!$title || !$subject || empty($_FILES['note_file']['name'])) {
        $error = "Please fill in all required fields and upload a file.";
    } else {
        $upload_dir = 'uploads/notes/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = basename($_FILES['note_file']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['note_file']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO notes (title, description, class, subject, file_path, uploaded_by, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssi", $title, $description, $teacher_class, $subject, $target_file, $teacher_id);
            $stmt->execute();
            $stmt->close();
            $success = "Note uploaded successfully!";
        } else {
            $error = "Failed to upload file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Upload Notes - Class <?= htmlspecialchars($teacher_class) ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Note Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload File (PDF, DOC, etc.)</label>
            <input type="file" name="note_file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload Note</button>
    </form>
    <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
