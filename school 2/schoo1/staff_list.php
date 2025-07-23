<?php
session_start();
require 'config.php';

// Delete staff
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'staff'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: staff_list.php");
    exit();
}

// Update staff
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_staff'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $class_teaching = trim($_POST['class_teaching']);

    $stmt = $conn->prepare("UPDATE users SET username = ?, full_name = ?, email = ?, class_teaching = ? WHERE id = ? AND role = 'staff'");
    $stmt->bind_param("ssssi", $username, $full_name, $email, $class_teaching, $id);
    $stmt->execute();
    header("Location: staff_list.php");
    exit();
}

// Fetch staff list
$result = $conn->query("SELECT id, username, full_name, email, class_teaching FROM users WHERE role = 'staff'");
$staff = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Staff List</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Class Teaching</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['username']) ?></td>
                    <td><?= htmlspecialchars($s['full_name']) ?></td>
                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><?= htmlspecialchars($s['class_teaching']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $s['id'] ?>">Edit</button>
                        <a href="?delete=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Staff</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($s['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($s['full_name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($s['email']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Class Teaching</label>
                                    <input type="text" name="class_teaching" class="form-control" value="<?= htmlspecialchars($s['class_teaching']) ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="update_staff" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
