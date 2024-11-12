<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)")
            ->execute([$user_id, $title, $description]);
    } elseif (isset($_POST['update_task'])) {
        $task_id = $_POST['task_id'];
        $status = $_POST['status'];
        $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?")
            ->execute([$status, $task_id, $user_id]);
    } elseif (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?")
            ->execute([$task_id, $user_id]);
    }
}

$tasks = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$tasks->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>My Tasks</h2>

    <form method="POST" class="mb-3">
        <div class="form-group">
            <input type="text" name="title" class="form-control" placeholder="Task Title" required>
        </div>
        <div class="form-group">
            <textarea name="description" class="form-control" placeholder="Task Description"></textarea>
        </div>
        <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch()) : ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                            <input type="hidden" name="update_task">
                        </form>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <button type="submit" name="delete_task" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
