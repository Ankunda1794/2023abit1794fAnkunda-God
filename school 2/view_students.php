<?php
session_start();
require('config.php');

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete from students table first
    $conn->query("DELETE FROM students WHERE user_id = $delete_id");

    // Delete from users table
    $conn->query("DELETE FROM users WHERE id = $delete_id");

    $_SESSION['message'] = "✅ Student deleted successfully!";
    header("Location: view_students.php");
    exit;
}
?>
<table class="table table-bordered">
    <thead>
        <tr>
        <th>NO</th>
        <th>Full name</th>
            <th>Class</th>
            <th>Stream</th>
            <th>Guardian Contact</th>
            <th>Student ID</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("
            SELECT u.id, u.full_name, u.username, s.class, s.stream, s.guardian_contact, s.student_id
            FROM users u
            INNER JOIN students s ON u.id = s.user_id
            ORDER BY s.class, s.stream
        ");
        $i = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['class']) ?></td>
            <td><?= htmlspecialchars($row['stream']) ?></td>
            <td><?= htmlspecialchars($row['guardian_contact']) ?></td>
            <td><?= htmlspecialchars($row['student_id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td>
                <a href="view_students.php?delete_id=<?= $row['id'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure you want to delete this student?');">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary"> Back</a>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">