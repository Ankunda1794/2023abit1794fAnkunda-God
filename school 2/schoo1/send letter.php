<?php
session_start();
require 'config.php';

// Check if user is logged in and has a position
if (!isset($_SESSION['username'], $_SESSION['position'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$position = $_SESSION['position'];
$message = "";

// Get sender user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$senderResult = $stmt->get_result();

if ($senderResult->num_rows === 0) {
    die("User not found.");
}

$sender = $senderResult->fetch_assoc();
$created_by_id = $sender['id'];
$stmt->close();

// Get secretary user ID (receiver)
$receiverStmt = $conn->prepare("SELECT id FROM users WHERE LOWER(position) = 'secretary' LIMIT 1");
$receiverStmt->execute();
$receiverResult = $receiverStmt->get_result();

if ($receiverResult->num_rows === 0) {
    die("No secretary found.");
}

$receiver = $receiverResult->fetch_assoc();
$receiver_id = $receiver['id'];
$receiverStmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $doc_type = trim($_POST['doc_type']);

    // Check for uploaded file and errors
    if (!empty($title) && !empty($doc_type) && isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        // Sanitize and create unique filename
        $originalFilename = basename($_FILES["document"]["name"]);
        $safeFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalFilename);

        $target_dir = __DIR__ . "/uploads/";
        $target_file = $target_dir . $safeFilename;

        // Create uploads directory if not exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            // Insert record into documents table with status 'pending'
            $stmt = $conn->prepare("INSERT INTO documents (title, doc_type, filename, created_by, receiver_id, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("sssii", $title, $doc_type, $safeFilename, $created_by_id, $receiver_id);

            if ($stmt->execute()) {
                $message = "✅ Letter sent successfully to Secretary.";
            } else {
                $message = "❌ Error saving letter to database.";
                // Remove uploaded file if DB insert fails
                unlink($target_file);
            }

            $stmt->close();
        } else {
            $message = "❌ File upload failed.";
        }
    } else {
        $message = "❌ Please fill all fields and upload a valid file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Send Letter to Secretary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Send Letter to Secretary</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm" novalidate>
        <div class="mb-3">
            <label for="title" class="form-label">Letter Title</label>
            <input type="text" id="title" name="title" class="form-control" required maxlength="255" />
        </div>
        <div class="mb-3">
            <label for="doc_type" class="form-label">Document Type</label>
            <input type="text" id="doc_type" name="doc_type" class="form-control" placeholder="e.g. Request, Complaint" required maxlength="100" />
        </div>
        <div class="mb-3">
            <label for="document" class="form-label">Upload Letter (PDF, DOC, DOCX)</label>
            <input type="file" id="document" name="document" class="form-control" accept=".pdf,.doc,.docx" required />
        </div>
        <button type="submit" class="btn btn-primary">Send Letter</button>
    </form>
</div>
</body>
</html>
