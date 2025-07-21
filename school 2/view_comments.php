<?php
session_start();
require 'config.php';

// ✅ Require login session with username and position
if (!isset($_SESSION['username'], $_SESSION['position'])) {
    die("Unauthorized access.");
}

$username = $_SESSION['username'];
$position = strtolower($_SESSION['position']);

// ✅ Confirm this user is actually the Headteacher from users table
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND LOWER(position) = 'headteacher'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Access denied. Only the Headteacher can view this page.");
}

$user = $result->fetch_assoc();
$headteacher_id = $user['id'];
$stmt->close();

// ✅ Fetch messages sent to the headteacher
$stmt = $conn->prepare("
    SELECT m.id, m.message, m.sent_at, m.reply, m.replied_at,
           u.username AS sender_username, u.position AS sender_position
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.receiver_id = ?
    ORDER BY m.sent_at DESC
");
$stmt->bind_param("i", $headteacher_id);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Comments - Headteacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Comments Received</h2>

    <?php if (empty($messages)): ?>
        <p class="text-muted">No comments received.</p>
    <?php else: ?>
        <?php foreach ($messages as $msg): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong><?= htmlspecialchars(ucfirst($msg['sender_position'])) ?> - <?= htmlspecialchars($msg['sender_username']) ?></strong>
                    <span class="float-end"><?= htmlspecialchars($msg['sent_at']) ?></span>
                </div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>

                    <?php if ($msg['reply']): ?>
                        <div class="alert alert-info">
                            <strong>Your Reply:</strong><br>
                            <?= nl2br(htmlspecialchars($msg['reply'])) ?><br>
                            <small class="text-muted">Replied at: <?= htmlspecialchars($msg['replied_at']) ?></small>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="reply_comment.php">
                            <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                            <div class="mb-2">
                                <textarea name="reply" class="form-control" rows="3" placeholder="Write your reply..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Send Reply</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
