<?php
session_start();
require 'config.php';

// ✅ Make sure only Headteacher can reply
if (!isset($_SESSION['username'], $_SESSION['position'])) {
    die("Unauthorized access.");
}

$loggedInUsername = $_SESSION['username'];
$loggedInPosition = strtolower($_SESSION['position']);

// ✅ Ensure only a Headteacher can reply
$check = $conn->prepare("SELECT id FROM users WHERE username = ? AND LOWER(position) = 'headteacher'");
$check->bind_param("s", $loggedInUsername);
$check->execute();
$result = $check->get_result();
if ($result->num_rows === 0) {
    die("Access denied. Only Headteacher can reply.");
}
$headteacher = $result->fetch_assoc();
$headteacher_id = $headteacher['id'];
$check->close();

// ✅ Handle the reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = intval($_POST['message_id']);
    $reply = trim($_POST['reply']);

    if (!$message_id || empty($reply)) {
        die("Invalid message or reply.");
    }

    // ✅ Update reply in messages table
    $stmt = $conn->prepare("
        UPDATE messages 
        SET reply = ?, replied_at = NOW() 
        WHERE id = ? AND receiver_id = ?
    ");
    $stmt->bind_param("sii", $reply, $message_id, $headteacher_id);
    
    if ($stmt->execute()) {
        // ✅ Redirect back with success
        header("Location: view_comments.php?reply=success");
        exit();
    } else {
        echo "Error updating reply: " . $stmt->error;
    }
    $stmt->close();
}
?>
