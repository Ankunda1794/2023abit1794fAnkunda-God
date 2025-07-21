<?php
require 'config.php';

// Fetch all staff records
$query = "SELECT id, user_id, username, full_name, email, gender, staff_type, department, qualifications, status, address, staff_position, created_at FROM staff ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("❌ Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">✅ Staff List</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Staff Type</th>
                <th>Department</th>
                <th>Qualifications</th>
                <th>Status</th>
                <th>Address</th>
                <th>Position</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>
                <td><?= htmlspecialchars($row['staff_type']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['qualifications']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['staff_position']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
