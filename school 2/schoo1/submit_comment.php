<?php
session_start();
require('config.php');

if ($_SESSION['role'] !== 'admin' || $_SESSION['position'] !== 'Director of Studies') {
    echo "Access Denied.";
    exit();
}

$director_name = $_SESSION['full_name'] ?? 'Director';

foreach ($_POST['performance_id'] as $id) {
    $comment = trim($_POST['comment'][$id]);
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO performance_comments (performance_id, comment, director_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $comment, $director_name);
        $stmt->execute();
    }
}

header("Location: director_dashboard.php");
exit();
