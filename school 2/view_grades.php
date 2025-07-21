<?php
session_start();
require 'config.php';

// Step 1: Confirm session
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please log in.");
}

$student_id = $_SESSION['uid'];

// Step 2: Confirm user is a student assigned to a class
$stmt = $conn->prepare("SELECT full_name, class, role FROM users WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user || strtolower($user['role']) !== 'student' || empty($user['class'])) {
    die("Access denied. You must be a student assigned to a class.");
}

$class = $user['class'];
$full_name = $user['full_name'];

// Step 3: Check outstanding balance from payments table
$stmt = $conn->prepare("SELECT COALESCE(SUM(remaining), 0) AS balance FROM payments WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

$balance = (float)$res['balance'];

if ($balance > 0) {
    die("<h3 style='text-align:center; color:red; margin-top:50px;'>❌ Access Denied: You still owe UGX " . number_format($balance) . ". Please clear your balance to view your report.</h3>");
}

// Step 4: Check if Director of Studies sent a report
$stmt = $conn->prepare("SELECT term_year, sent_at FROM sent_reports WHERE student_id = ? AND class = ? ORDER BY sent_at DESC LIMIT 1");
$stmt->bind_param("is", $student_id, $class);
$stmt->execute();
$res = $stmt->get_result();
$report = $res->fetch_assoc();
$stmt->close();

if (!$report) {
    die("<h3 style='text-align:center; margin-top:50px;'>📭 No report has been shared with you yet. Please check again later.</h3>");
}

$term_year = $report['term_year'];
$sent_date = $report['sent_at'];

// Step 5: Fetch student marks
$stmt = $conn->prepare("SELECT subject, u1, u2, u3, u4, mt, eot FROM student_performance WHERE student_id = ? AND class = ?");
$stmt->bind_param("is", $student_id, $class);
$stmt->execute();
$res = $stmt->get_result();

$marks = [];
while ($row = $res->fetch_assoc()) {
    $uavg = round(($row['u1'] + $row['u2'] + $row['u3'] + $row['u4']) / 4, 2);
    $total80 = round($row['mt'] * 0.3 + $row['eot'] * 0.7, 2);
    $total = round($uavg + $total80, 2);
    $grade = match (true) {
        $total >= 80 => 'A',
        $total >= 70 => 'B',
        $total >= 60 => 'C',
        $total >= 50 => 'D',
        $total >= 40 => 'E',
        default => 'F',
    };
    $marks[] = [
        'subject' => $row['subject'],
        'u1' => $row['u1'], 'u2' => $row['u2'], 'u3' => $row['u3'], 'u4' => $row['u4'],
        'uavg' => $uavg,
        'mt' => $row['mt'],
        'eot' => $row['eot'],
        'total80' => $total80,
        'total' => $total,
        'grade' => $grade
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Report Card</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; }
        .info { margin-bottom: 20px; }
        .info strong { display: inline-block; width: 160px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background: #2c3e50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📄 Academic Report Card</h2>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($full_name) ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($class) ?></p>
            <p><strong>Report Sent:</strong> <?= htmlspecialchars($sent_date) ?> | Term: <?= htmlspecialchars($term_year) ?></p>
        </div>

        <?php if (empty($marks)): ?>
            <p>No performance records found for you at this time.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">Subject</th>
                        <th colspan="5">Formative (U1–U4)</th>
                        <th colspan="2">Summative</th>
                        <th colspan="3">Term Summary</th>
                    </tr>
                    <tr>
                        <th>U1</th><th>U2</th><th>U3</th><th>U4</th><th>Average</th>
                        <th>MT</th><th>EOT</th>
                        <th>Total /80</th><th>Total</th><th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marks as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['subject']) ?></td>
                            <td><?= $m['u1'] ?></td>
                            <td><?= $m['u2'] ?></td>
                            <td><?= $m['u3'] ?></td>
                            <td><?= $m['u4'] ?></td>
                            <td><?= $m['uavg'] ?></td>
                            <td><?= $m['mt'] ?></td>
                            <td><?= $m['eot'] ?></td>
                            <td><?= $m['total80'] ?></td>
                            <td><?= $m['total'] ?></td>
                            <td><?= $m['grade'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
