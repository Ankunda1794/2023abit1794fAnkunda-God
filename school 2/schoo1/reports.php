<?php
session_start();
require('config.php');

// Only bursar or admin can access
if (!isset($_SESSION['role'], $_SESSION['position']) || 
    $_SESSION['role'] !== 'admin' || 
    strtolower(trim($_SESSION['position'])) !== 'bursar') {
    echo "<div style='padding: 2rem; font-family: sans-serif; color: red;'>🚫 Access Denied.</div>";
    exit();
}

// Filters (no class filter now)
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build SQL
$sql = "
    SELECT 
        u.full_name,
        u.class,
        p.amount_paid,
        p.remaining,
        p.payment_date,
        p.status
    FROM payments p
    JOIN users u ON p.student_id = u.id
    WHERE 1=1
";

// Dynamic filters
$params = [];
$types = '';

if (!empty($status_filter)) {
    $sql .= " AND p.status = ?";
    $types .= 's';
    $params[] = $status_filter;
}

if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND p.payment_date BETWEEN ? AND ?";
    $types .= 'ss';
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY p.payment_date DESC";

// Execute query
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; }
        .filter-form .form-control, .filter-form .btn { margin-right: 10px; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
    </style>
</head>
<body>

<h2 class="mb-4 text-primary"> General Student Payment Report</h2>
<a href="bursar_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

<!-- Filter Form -->
<form method="GET" class="d-flex mb-4 filter-form flex-wrap">
    <select name="status" class="form-control mb-2">
        <option value="">-- Status --</option>
        <option value="verified" <?= $status_filter === 'verified' ? 'selected' : '' ?>>Verified</option>
        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
    </select>

    <input type="date" name="start_date" class="form-control mb-2" value="<?= htmlspecialchars($start_date) ?>">
    <input type="date" name="end_date" class="form-control mb-2" value="<?= htmlspecialchars($end_date) ?>">

    <button type="submit" class="btn btn-primary mb-2">Filter</button>
</form>

<?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Amount Paid (UGX)</th>
                <th>Remaining (UGX)</th>
                <th>Payment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $n = 1; 
            $total_paid = 0;
            $total_remaining = 0;
            while ($row = $result->fetch_assoc()): 
                $total_paid += $row['amount_paid'];
                $total_remaining += $row['remaining'];
            ?>
                <tr>
                    <td><?= $n++ ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['class']) ?></td>
                    <td><?= number_format($row['amount_paid']) ?></td>
                    <td>
                        <?= $row['remaining'] > 0 
                            ? "<span class='text-danger fw-bold'>" . number_format($row['remaining']) . "</span>"
                            : "<span class='text-success fw-bold'>Cleared</span>" ?>
                    </td>
                    <td><?= date('d M Y', strtotime($row['payment_date'])) ?></td>
                    <td>
                        <?php if ($row['status'] === 'verified'): ?>
                            <span class="badge bg-success">Verified</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td><?= number_format($total_paid) ?> UGX</td>
                <td><?= number_format($total_remaining) ?> UGX</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No payment records found.</div>
<?php endif; ?>
<a href="bursar_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

</body>
</html>
