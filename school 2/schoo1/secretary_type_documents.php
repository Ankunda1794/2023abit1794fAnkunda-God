<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "<h2>Access Denied</h2><p><a href='login.php'>Login</a></p>";
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT role, position FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2>User Not Found</h2>";
    exit();
}

$user = $result->fetch_assoc();
if (strtolower($user['role']) !== 'staff' || strtolower($user['position']) !== 'secretary') {
    echo "<h2>Access Denied</h2><p>Unauthorized access.</p>";
    exit();
}

$uploadSuccess = "";
$uploadError = "";

// Handle upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["document"])) {
    $title = trim($_POST["title"]);
    $docType = $_POST["doc_type"];

    $targetDir = "uploads/";
    $originalFileName = basename($_FILES["document"]["name"]);
    $uniqueFileName = time() . "_" . $originalFileName;
    $targetFile = $targetDir . $uniqueFileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (empty($title) || empty($docType)) {
        $uploadError = "❌ Please enter the document title and select the type.";
    } elseif ($fileType !== "docx") {
        $uploadError = "❌ Only .docx files are allowed.";
    } elseif (move_uploaded_file($_FILES["document"]["tmp_name"], $targetFile)) {
        // Save info to database
        $stmt = $conn->prepare("INSERT INTO documents (title, doc_type, filename, created_by, status) VALUES (?, ?, ?, ?, 'Submitted')");
        $stmt->bind_param("ssss", $title, $docType, $uniqueFileName, $username);
        if ($stmt->execute()) {
            $uploadSuccess = "✅ Document uploaded and submitted successfully.";
        } else {
            $uploadError = "❌ Upload succeeded, but failed to save to database.";
        }
    } else {
        $uploadError = "❌ Upload failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secretary - Official Document</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; text-align: center; }
        h2 { margin-bottom: 20px; }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }
        .btn:hover { background: #0056b3; }
        .success { color: green; margin-top: 20px; font-weight: bold; }
        .error { color: red; margin-top: 20px; font-weight: bold; }
        .upload-box {
            margin-top: 30px;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 0 10px #ccc;
            text-align: left;
            max-width: 500px;
        }
        input[type="text"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <h2>📤 Submit Completed Document</h2>

    <div class="upload-box">
        <form method="POST" enctype="multipart/form-data">
            <label>📌 Document Title:</label>
            <input type="text" name="title" required>

            <label>📂 Document Type:</label>
            <select name="doc_type" required>
                <option value="">-- Select Type --</option>
                <option value="Memo">Memo</option>
                <option value="Admission Letter">Admission Letter</option>
                <option value="Transfer Certificate">Transfer Certificate</option>
                <option value="Report">Report</option>
                <option value="Other">Other</option>
            </select>

            <label>📎 Upload Word File (.docx):</label>
            <input type="file" name="document" accept=".docx" required>

            <button type="submit" class="btn">Upload to Headteacher</button>
        </form>

        <?php if ($uploadSuccess): ?>
            <div class="success"><?= htmlspecialchars($uploadSuccess) ?></div>
        <?php elseif ($uploadError): ?>
            <div class="error"><?= htmlspecialchars($uploadError) ?></div>
        <?php endif; ?>
    </div>

</body>
</html>
