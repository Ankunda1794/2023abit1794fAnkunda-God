<?php
session_start();
require 'config.php';

// Check login
if (!isset($_SESSION['username'], $_SESSION['position'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$position = strtolower($_SESSION['position']);

// Get current user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$userData = $result->fetch_assoc();
$user_id = $userData['id'];
$stmt->close();

// Fetch replies to this user (sent by any role)
$query = "
    SELECT 
        m.message,
        m.reply,
        m.sent_at,
        m.replied_at,
        u.username AS replier_username,
        u.position AS replier_position
    FROM messages m
    JOIN users u ON m.receiver_id = u.id
    WHERE 
        m.sender_id = ?
        AND m.reply IS NOT NULL
        AND TRIM(m.reply) != ''
    ORDER BY m.replied_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$replies = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Replies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Replies to Your Messages</h2>

    <?php if (empty($replies)): ?>
        <p class="text-muted">No replies yet.</p>
    <?php else: ?>
        <?php foreach ($replies as $reply): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <strong><?= htmlspecialchars(ucfirst($reply['replier_position'])) ?> - <?= htmlspecialchars($reply['replier_username']) ?></strong>
                    <span class="float-end"><?= htmlspecialchars($reply['replied_at']) ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Your Message:</strong><br><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
                    <hr>
                    <p><strong>Reply:</strong><br><?= nl2br(htmlspecialchars($reply['reply'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
