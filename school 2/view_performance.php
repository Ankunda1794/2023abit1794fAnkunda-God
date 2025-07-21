<?php
session_start();
require 'config.php';

// Validate access for Director of Studies
if (!isset($_SESSION['uid'])) die("Access denied.");
$stmt = $conn->prepare("SELECT position FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['uid']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (strtolower(trim($user['position'])) !== 'director of studies') die("Access denied.");
$stmt->close();

// Filters
$levels = ['S1','S2','S3','S4','S5','S6'];
$terms = [1,2,3];
$class = $_GET['class'] ?? 'S1';
$term = in_array((int)($_GET['term'] ?? 1), $terms) ? (int)$_GET['term'] : 1;
$student_id = $_GET['student_id'] ?? '';

// Get students
$students = [];
$s = $conn->prepare("SELECT id, full_name FROM users WHERE role='student' AND class=?");
$s->bind_param("s", $class);
$s->execute();
foreach ($s->get_result() as $r) {
    $students[$r['id']] = $r['full_name'];
}
$s->close();

// Fetch records
$records = [];
if ($student_id) {
    $q = $conn->prepare("SELECT subject, U1, U2, U3, U4, MT, EOT, total, grade FROM student_performance WHERE student_id=? AND class=? AND term=?");
    $q->bind_param("isi", $student_id, $class, $term);
    $q->execute();
    $records = $q->get_result()->fetch_all(MYSQLI_ASSOC);
    $q->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Report</title>
    <style>
        body{font-family:Arial;background:#f4f4f4;padding:20px;}
        form,table{width:100%;background:#fff;padding:15px;border-radius:8px;margin-bottom:20px;}
        select{padding:8px;margin-right:8px;}
        table{border-collapse:collapse;}
        th,td{border:1px solid #ccc;padding:8px;text-align:center;}
        th{background:#2c3e50;color:#fff;}
        tr:nth-child(even){background:#f9f9f9;}
        button{padding:6px 12px;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        button:hover{background:#1d4ed8;}
    </style>
</head>
<body>

<h2>Student Report – <?= htmlspecialchars($class) ?> Term <?= $term ?></h2>

<form method="get">
    <select name="class" onchange="this.form.submit()">
        <?php foreach($levels as $lvl): ?>
            <option <?= $lvl==$class?'selected':'' ?>><?= $lvl ?></option>
        <?php endforeach ?>
    </select>
    <select name="term" onchange="this.form.submit()">
        <?php foreach($terms as $t): ?>
            <option value="<?= $t ?>" <?= $t==$term?'selected':'' ?>>Term <?= $t ?></option>
        <?php endforeach ?>
    </select>
    <select name="student_id" onchange="this.form.submit()">
        <option value="">-- Select Student --</option>
        <?php foreach($students as $id=>$n): ?>
            <option value="<?= $id ?>" <?= $id==$student_id?'selected':'' ?>><?= $n ?> (<?= $id ?>)</option>
        <?php endforeach ?>
    </select>
</form>

<?php if ($student_id): ?>
    <?php if ($records): ?>
        <table>
            <tr>
                <th>Subject</th><th>U1</th><th>U2</th><th>U3</th><th>U4</th><th>MT</th><th>EOT</th><th>Total</th><th>Grade</th><th>Edit</th>
            </tr>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['subject']) ?></td>
                    <td><?= $r['U1'] ?></td>
                    <td><?= $r['U2'] ?></td>
                    <td><?= $r['U3'] ?></td>
                    <td><?= $r['U4'] ?></td>
                    <td><?= $r['MT'] ?></td>
                    <td><?= $r['EOT'] ?></td>
                    <td><?= $r['total'] ?></td>
                    <td><?= $r['grade'] ?></td>
                    <td>
                        <form method="get" action="edit_marks.php">
                            <input type="hidden" name="student_id" value="<?= $student_id ?>">
                            <input type="hidden" name="class" value="<?= $class ?>">
                            <input type="hidden" name="term" value="<?= $term ?>">
                            <input type="hidden" name="subject" value="<?= htmlspecialchars($r['subject']) ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php else: ?>
        <p>No performance data yet.</p>
    <?php endif ?>
<?php endif ?>
</body>
</html>
