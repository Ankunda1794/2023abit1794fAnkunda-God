<?php
session_start();
require('config.php');

// Ensure user is logged in and has permission
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['assignment_file'])) {
        $file = $_FILES['assignment_file'];
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($_SESSION['username'] . '_' . $file['name']); // Append username

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $message = "✅ Assignment uploaded successfully.";
        } else {
            $message = "❌ Failed to upload assignment.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Upload an Assignment</h3>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="assignment_file" class="form-label">Choose Assignment File</label>
            <input type="file" name="assignment_file" id="assignment_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <h3 class="mt-4">Uploaded Assignments</h3>
    <ul class="list-group">
        <?php
        // Display uploaded assignments
        $files = scandir('uploads/');
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && strpos($file, $_SESSION['username'] . '_') === 0) { // Show only user's files
                echo '<li class="list-group-item">';
                echo htmlspecialchars($file);
                echo ' <a href="download.php?file=' . urlencode($file) . '" class="btn btn-secondary btn-sm">Download</a>';
                echo '</li>';
            }
        }
        ?>
    </ul>
</div>
</body>
</html>