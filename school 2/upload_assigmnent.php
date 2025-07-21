<?php
session_start();
require 'config.php';

if ($_SESSION['role'] !== 'staff') {
    echo "Access Denied.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $class = $_POST['class'];  // e.g., "S4"
    $staff_id = $_SESSION['user_id'];

    $file_path = "";
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $file_path = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
    }

    $stmt = $conn->prepare("INSERT INTO assignments (title, description, class, file_path, staff_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $class, $file_path, $staff_id);
    $stmt->execute();

    echo "✅ Assignment uploaded successfully.";
}
?>

<!-- Upload form -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <select name="class" required>
        <option value="S1">S1</option>
        <option value="S2">S2</option>
        <option value="S3">S3</option>
        <option value="S4">S4</option>
        <option value="S5">S5</option>
        <option value="S6">S6</option>
    </select><br>
    <input type="file" name="file"><br>
    <button type="submit">Upload</button>
</form>
