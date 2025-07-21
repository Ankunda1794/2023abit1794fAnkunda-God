<?php
session_start();
require 'config.php';

// Check user is logged in as staff and position teacher
if (!isset($_SESSION['uid'], $_SESSION['role'], $_SESSION['position']) || 
    $_SESSION['role'] !== 'staff' || strtolower($_SESSION['position']) !== 'teacher') {
    die("Access denied. Only teachers can access this page.");
}

$teacher_name = $_SESSION['full_name'];
$class_teaching = $_SESSION['class_teaching'];
$uid = $_SESSION['uid'];

// Fetch assignments for the class this teacher teaches
$stmt = $conn->prepare("SELECT id, title, created_at FROM assignments WHERE class = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $class_teaching);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 900px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { margin: 0 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background: #f2f2f2; }
        a.button {
            background: #3498db; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; display: inline-block;
        }
        a.button:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?= htmlspecialchars($teacher_name) ?></h1>
    <p>Class Teaching: <strong><?= htmlspecialchars($class_teaching) ?></strong></p>
</div>

<div class="container">
    <a href="create_assignment.php" class="button">+ Upload New Assignment</a>

    <h2>Your Assignments</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Uploaded On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($assignment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($assignment['title']) ?></td>
                        <td><?= date("F j, Y, g:i a", strtotime($assignment['created_at'])) ?></td>
                        <td>
                            <a href="view_assignment.php?id=<?= $assignment['id'] ?>">View</a>
                            <!-- Optionally add edit/delete links here -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assignments uploaded yet.</p>
    <?php endif; ?>
</div>
<a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
