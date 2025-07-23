<?php
session_start();
require('config.php');

// Ensure student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student_username = $_SESSION['username'];
$current_year = "2025";

// Get student ID and class
$stmt = $conn->prepare("SELECT id, class FROM users WHERE username = ?");
$stmt->bind_param("s", $student_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student not found.");
}

$user = $result->fetch_assoc();
$student_id = $user['id'];
$current_class = $user['class'];
$stmt->close();

// Check if student has any report yet
$report_check = $conn->prepare("SELECT id FROM sent_reports WHERE student_id = ? LIMIT 1");
$report_check->bind_param("i", $student_id);
$report_check->execute();
$has_report = $report_check->get_result()->num_rows > 0;
$report_check->close();

// Define the current term (can be dynamic if needed)
$current_term = date('n') >= 1 && date('n') <= 4 ? '1' : (date('n') <= 8 ? '2' : '3');

if ($has_report) {
    // Student has a report — must be enrolled for current term
    $check = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND term = ? AND academic_year = ?");
    $check->bind_param("iss", $student_id, $current_term, $current_year);
    $check->execute();
    $enrolled = $check->get_result()->num_rows > 0;
    $check->close();

    // Redirect to enrollment if not enrolled
    if (!$enrolled) {
        header("Location: enroll_now.php");
        exit();
    }
}

// Optional success message
$success_message = '';
if (isset($_SESSION['message'])) {
    $success_message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🎓 Student Dashboard</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Welcome, <?= htmlspecialchars($student_username) ?> (<?= htmlspecialchars($current_class) ?>)
            </span>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card mb-3">
                <div class="card-header bg-primary text-white text-center">
                    📚 My Class: <?= htmlspecialchars($current_class) ?> | Term <?= $current_term ?>
                </div>
                <div class="card-body">

                    <div class="d-grid gap-3">
                        <a href="view_timetable.php" class="btn btn-outline-primary">📆 View Timetable</a>
                        <a href="view_grades.php" class="btn btn-outline-success">📈 View Grades</a>
                        <a href="view_assignments.php" class="btn btn-outline-secondary">📝 View Assignments</a>
                        <a href="submit_assignment.php" class="btn btn-outline-warning">📤 Submit Assignment</a>
                        <a href="view_notes.php" class="btn btn-outline-info">📖 View Notes</a>
                        <a href="pay_fees.php" class="btn btn-outline-danger">💳 Pay Fees</a>
                        <a href="my_transactions.php" class="btn btn-outline-dark">🧾 My Transactions</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
