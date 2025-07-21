<?php
session_start();
require('config.php');

// Check if user is logged in as admin bursar
if (!isset($_SESSION['role'], $_SESSION['position']) || 
    $_SESSION['role'] !== 'admin' || 
    strtolower(trim($_SESSION['position'])) !== 'bursar') {
    echo "Access denied.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $payment_id = intval($_POST['payment_id']);

    // Prepare update statement to set status = 'verified'
    $stmt = $conn->prepare("UPDATE payments SET status = 'verified' WHERE id = ?");
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        // Redirect back to dashboard with success message (optional)
        header("Location: bursar_dashboard.php?msg=Payment+marked+as+verified");
        exit();
    } else {
        echo "Error updating payment status.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
