<?php
session_start();
require 'config.php';

if (!isset($_SESSION['uid'])) die("Access denied");

$teacher_id = $_SESSION['uid'];
$teacher_stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$teacher_stmt->bind_param("i", $teacher_id);
$teacher_stmt->execute();
$teacher = $teacher_stmt->get_result()->fetch_assoc();
$teacher_name = $teacher['full_name'] ?? 'Unknown';

$levels = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'];
$subjects = ['English Language', 'Geography', 'History', 'Entrepreneurship', 'Mathematics', 'Biology', 'Physics', 'Chemistry', 'Physical Education', 'Christian Rel. Educ.', 'Computer Studies', 'Kiswahili', 'Literature in Eng'];

$class = $_GET['class'] ?? 'S1';
$term = $_GET['term'] ?? 1;

// Fetch students
$students = [];
$stmt = $conn->prepare("SELECT id, full_name FROM users WHERE role = 'student' AND class = ?");
$stmt->bind_param("s", $class);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $students[$row['id']] = $row['full_name'];
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    $subject = $_POST['subject'] ?? '';
    $class = $_POST['class'] ?? $class;
    $term = $_POST['term'] ?? $term;

    // Convert and sanitize scores
    $U1 = isset($_POST['U1']) && $_POST['U1'] !== '' ? (float)$_POST['U1'] : 0;
    $U2 = isset($_POST['U2']) && $_POST['U2'] !== '' ? (float)$_POST['U2'] : 0;
    $U3 = isset($_POST['U3']) && $_POST['U3'] !== '' ? (float)$_POST['U3'] : 0;
    $U4 = isset($_POST['U4']) && $_POST['U4'] !== '' ? (float)$_POST['U4'] : 0;
    $MT = isset($_POST['MT']) && $_POST['MT'] !== '' ? (float)$_POST['MT'] : 0;
    $EOT = isset($_POST['EOT']) && $_POST['EOT'] !== '' ? (float)$_POST['EOT'] : 0;

    // Only process if valid student/subject and scores entered
    if ($student_id && $subject && ($U1 + $U2 + $U3 + $U4 + $MT + $EOT) > 0) {
        $avg = round(($U1 + $U2 + $U3 + $U4) / 4, 2);
        $total80 = round($MT * 0.3 + $EOT * 0.7, 2);
        $totalMark = round($avg + $total80, 2);

        // Grade logic
        $grade = match (true) {
            $totalMark >= 80 => 'A',
            $totalMark >= 70 => 'B',
            $totalMark >= 60 => 'C',
            $totalMark >= 50 => 'D',
            $totalMark >= 40 => 'E',
            default => 'F',
        };

        // Save to database
        $stmt = $conn->prepare("REPLACE INTO student_performance 
            (student_id, subject, term, class, U1, U2, U3, U4, average, MT, EOT, total, grade, submitted_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isissddddddsss", // Correct types: s = string, d = double, i = integer
            $student_id,
            $subject,
            $term,
            $class,
            $U1, $U2, $U3, $U4,
            $avg,
            $MT,
            $EOT,
            $totalMark,
            $grade,
            $teacher_name
        );
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Performance recorded successfully for $subject.');</script>";
    } else {
        echo "<script>alert('Please fill in at least one mark and select a student/subject.');</script>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Record Student Performance</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .filter-form,
        .entry-form {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px 30px;
            max-width: 900px;
            margin: 0 auto 30px;
        }

        .filter-form select {
            width: 150px;
            padding: 8px;
            margin-right: 15px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #1e3a8a;
            color: white;
        }

        td input[type="number"] {
            width: 80px;
            padding: 6px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: 500;
        }

        select, button {
            padding: 10px;
            font-size: 15px;
            margin-top: 5px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

<h2>Record Performance – <?= htmlspecialchars($class) ?> – Term <?= htmlspecialchars($term) ?></h2>

<form method="get" class="filter-form">
    <label>
        Class:
        <select name="class" onchange="this.form.submit()">
            <?php foreach ($levels as $lvl): ?>
                <option value="<?= $lvl ?>" <?= $lvl === $class ? 'selected' : '' ?>><?= $lvl ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Term:
        <select name="term" onchange="this.form.submit()">
            <?php foreach ([1, 2, 3] as $t): ?>
                <option value="<?= $t ?>" <?= $t == $term ? 'selected' : '' ?>>Term <?= $t ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<form method="post" class="entry-form">
    <input type="hidden" name="class" value="<?= htmlspecialchars($class) ?>">
    <input type="hidden" name="term" value="<?= htmlspecialchars($term) ?>">

    <label>
        Student:
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($students as $id => $name): ?>
                <option value="<?= $id ?>"><?= htmlspecialchars($name) ?> (<?= $id ?>)</option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Subject:
        <select name="subject" required>
            <option value="">-- Select Subject --</option>
            <?php foreach ($subjects as $sub): ?>
                <option value="<?= htmlspecialchars($sub) ?>"><?= htmlspecialchars($sub) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <table>
        <thead>
            <tr>
                <th>U1</th>
                <th>U2</th>
                <th>U3</th>
                <th>U4</th>
                <th>Mid Term (30%)</th>
                <th>End of Term (70%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="number" name="U1" min="0" max="20" step="0.01"></td>
                <td><input type="number" name="U2" min="0" max="20" step="0.01"></td>
                <td><input type="number" name="U3" min="0" max="20" step="0.01"></td>
                <td><input type="number" name="U4" min="0" max="20" step="0.01"></td>
                <td><input type="number" name="MT" min="0" max="30" step="0.01"></td>
                <td><input type="number" name="EOT" min="0" max="70" step="0.01"></td>
            </tr>
        </tbody>
    </table>

   
    <button type="submit">Submit Performance</button>
<a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</form>



</body>
</html>
