<?php
session_start();
require('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['payment_id'])) {
    $payment_id = intval($_POST['payment_id']);

    if ($_SESSION['position'] === 'Bursar') {
        $stmt = $conn->prepare("UPDATE fee_payments SET status = 'verified' WHERE id = ?");
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
    }

    header("Location: bursar_dashboard.php");
    exit();
}
?>
