<?php
session_start();
require 'config.php';

// Auth check
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please log in.");
}

$classes = ['S1','S2','S3','S4','S5','S6'];
$selected_class = $_GET['class'] ?? 'S1';

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_class'])) {
    $class_to_submit = $_POST['class_to_submit'] ?? '';
    if (!in_array($class_to_submit, $classes)) {
        $error = "Invalid class selected.";
    } else {
        $stmt = $conn->prepare("
            UPDATE student_performance sp
            JOIN users u ON sp.student_id = u.id
            SET sp.submitted_to_director = 1, sp.submitted_at = NOW()
            WHERE u.class = ? AND sp.submitted_to_director = 0
        ");
        $stmt->bind_param("s", $class_to_submit);

        if ($stmt->execute()) {
            $success = "All student performances for class {$class_to_submit} submitted successfully.";
        } else {
            $error = "Error submitting performances for class {$class_to_submit}.";
        }
        $stmt->close();
    }
}

// Fetch performances for the selected class
$stmt = $conn->prepare("
    SELECT 
        sp.student_id, 
        u.full_name AS student_name, 
        sp.subject, 
        sp.continuous_assessment, 
        sp.exam_score, 
        sp.term, 
        sp.academic_year,
        sp.submitted_to_director,
        sp.submitted_at
    FROM student_performance sp
    JOIN users u ON u.id = sp.student_id
    WHERE u.class = ?
    ORDER BY u.full_name, sp.subject
");
$stmt->bind_param("s", $selected_class);
$stmt->execute();
$res = $stmt->get_result();

$records = [];
while ($row = $res->fetch_assoc()) {
    $sid = $row['student_id'];
    if (!isset($records[$sid])) {
        $records[$sid] = [
            'name' => $row['student_name'],
            'performances' => []
        ];
    }
    $records[$sid]['performances'][] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Student Performance by Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Submit Student Performance to Director of Studies</h2>

<!-- Class selection form -->
<form method="GET" class="mb-4">
    <label for="class">Select Class:</label>
    <select id="class" name="class" onchange="this.form.submit()" class="form-select w-auto d-inline-block ms-2">
        <?php foreach ($classes as $cls): ?>
            <option value="<?= htmlspecialchars($cls) ?>" <?= $cls === $selected_class ? 'selected' : '' ?>>
                <?= htmlspecialchars($cls) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (count($records) > 0): ?>
    <?php foreach ($records as $student_id => $data): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <?= htmlspecialchars($data['name']) ?>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-sm mb-2">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>CA</th>
                            <th>Exam</th>
                            <th>Term</th>
                            <th>Year</th>
                            <th>Submitted?</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['performances'] as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['subject']) ?></td>
                                <td><?= htmlspecialchars($p['continuous_assessment']) ?></td>
                                <td><?= htmlspecialchars($p['exam_score']) ?></td>
                                <td><?= htmlspecialchars($p['term']) ?></td>
                                <td><?= htmlspecialchars($p['academic_year']) ?></td>
                                <td><?= $p['submitted_to_director'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning text-dark">No</span>' ?></td>
                                <td><?= $p['submitted_at'] ? htmlspecialchars($p['submitted_at']) : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Submit all for the class -->
    <form method="POST" onsubmit="return confirm('Submit all performances for class <?= htmlspecialchars($selected_class) ?>?');">
        <input type="hidden" name="class_to_submit" value="<?= htmlspecialchars($selected_class) ?>">
        <button type="submit" name="submit_class" class="btn btn-success">Submit All for Class <?= htmlspecialchars($selected_class) ?></button>
    </form>

<?php else: ?>
    <div class="alert alert-info">No student performances found for class <?= htmlspecialchars($selected_class) ?>.</div>
<?php endif; ?>

</body>
</html>
