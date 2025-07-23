<?php
session_start();
require 'config.php';

// Only allow logged-in students
if (!isset($_SESSION['uid'], $_SESSION['role'], $_SESSION['class']) || $_SESSION['role'] !== 'student') {
    die("Access denied. Only students can submit assignments.");
}

$student_id = $_SESSION['uid'];
$student_class = $_SESSION['class'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignment_id = intval($_POST['assignment_id']);
    $subject = trim($_POST['subject']);

    if (empty($assignment_id) || empty($subject) || !isset($_FILES['submission_file'])) {
        $error = "All fields are required.";
    } else {
        $file = $_FILES['submission_file'];
        $allowed_types = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($file['type'], $allowed_types)) {
            $error = "Invalid file type. Only PDF and Word documents are allowed.";
        } else {
            $filename = time() . "_" . basename($file['name']);
            $target_dir = "uploads/submissions/";
            $target_path = $target_dir . $filename;

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Find teacher ID who teaches this subject in this class
                $teacher_stmt = $conn->prepare("
                    SELECT id FROM users 
                    WHERE role = 'staff' AND position = 'teacher' 
                      AND LOWER(subject) = LOWER(?) AND class_teaching = ?
                    LIMIT 1
                ");
                $teacher_stmt->bind_param("ss", $subject, $student_class);
                $teacher_stmt->execute();
                $teacher_result = $teacher_stmt->get_result();
                $teacher = $teacher_result->fetch_assoc();
                $teacher_stmt->close();

                if (!$teacher) {
                    $error = "No teacher found for subject '$subject' in your class.";
                    // Delete uploaded file since no teacher found
                    unlink($target_path);
                } else {
                    $teacher_id = $teacher['id'];

                    $stmt = $conn->prepare("INSERT INTO assignment_submissions (assignment_id, student_id, teacher_id, subject, file_path, submitted_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    $stmt->bind_param("iiiss", $assignment_id, $student_id, $teacher_id, $subject, $target_path);

                    if ($stmt->execute()) {
                        $success = "✅ Assignment submitted successfully.";
                    } else {
                        $error = "❌ Failed to save submission to database.";
                        // Delete uploaded file on failure
                        unlink($target_path);
                    }
                    $stmt->close();
                }
            } else {
                $error = "❌ Failed to upload file.";
            }
        }
    }
}

// Fetch assignments for student's class
$assignments = [];
$stmt = $conn->prepare("SELECT id, title FROM assignments WHERE class = ?");
$stmt->bind_param("s", $student_class);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Submit Assignment</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="assignment_id">Select Assignment</label>
            <select name="assignment_id" class="form-control" required>
                <option value="">-- Choose Assignment --</option>
                <?php foreach ($assignments as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Subject (Enter exactly as taught)</label>
            <input type="text" name="subject" required class="form-control" placeholder="e.g., Mathematics">
        </div>
        <div class="mb-3">
            <label>Upload Your Work (PDF/Word)</label>
            <input type="file" name="submission_file" accept=".pdf,.doc,.docx" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit Assignment</button>
        <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</body>
</html>
