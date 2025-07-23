<?php
session_start();
require('config.php');

// Check if user is logged in and is Headteacher
if (!isset($_SESSION['role'], $_SESSION['position']) || 
    $_SESSION['role'] !== 'admin' || 
    $_SESSION['position'] !== 'Headteacher') {
    echo "🚫 Access Denied. Only Headteacher can view staff messages.";
    exit();
}

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $msg_id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE staff_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $msg_id);
    $stmt->execute();
    header("Location: staff_messages.php");
    exit();
}

// Fetch messages
$sql = "SELECT m.*, u.full_name FROM staff_messages m 
        JOIN users u ON m.staff_id = u.id 
        ORDER BY m.sent_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title> Messages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f4f4; padding: 20px; }
        .message-card { border-left: 5px solid #007bff; }
        .unread { background-color: #e7f1ff; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">  Messages & Feedback</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card mb-3 shadow-sm message-card <?= $row['is_read'] ? '' : 'unread' ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['full_name']) ?> (ID: <?= htmlspecialchars($row['staff_id']) ?>)</h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= date('d M Y, h:i A', strtotime($row['sent_at'])) ?></h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($row['message'])) ?></p>

                        <?php if (!$row['is_read']): ?>
                            <a href="?mark_read=<?= $row['id'] ?>" class="btn btn-sm btn-success">✅ Mark as Read</a>
                        <?php else: ?>
                            <span class="badge bg-success">✔ Read</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">No  messages found.</div>
        <?php endif; ?>

        <a href="admin_dashboard.php" class="btn btn-secondary mt-3"> Back </a>
    </div>
</body>
</html>
