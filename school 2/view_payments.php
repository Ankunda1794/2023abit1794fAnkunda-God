<?php
session_start();
require('config.php');

// Access check for bursar only
if (!isset($_SESSION['role'], $_SESSION['position']) || 
    $_SESSION['role'] !== 'admin' || 
    strtolower(trim($_SESSION['position'])) !== 'bursar') {
    
    echo "<div style='padding: 2rem; font-family: sans-serif; color: red;'>
            🚫 Access Denied. Only Bursars can access this page.
          </div>";
    exit();
}

// Fetch all verified payments with student details
$query = "
    SELECT 
        u.full_name,
        u.class,
        p.amount_paid,
        p.payment_date
    FROM payments p
    JOIN users u ON p.student_id = u.id
    WHERE p.status = 'verified'
    ORDER BY p.payment_date DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Verified Payments - Bursar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">✅ Verified Student Payments</h2>
    <a href="bursar_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Amount Paid (UGX)</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= number_format($row['amount_paid']) ?></td>
                        <td><?= htmlspecialchars(date("d M Y", strtotime($row['payment_date']))) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No verified payments found yet.</div>
    <?php endif; ?>
</div>
</body>
</html>
