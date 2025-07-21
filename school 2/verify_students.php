<?php
require('config.php');

// Handle student approval
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $result = $conn->query("SELECT * FROM student_pending WHERE id = $id");

    if ($result && $r = $result->fetch_assoc()) {
        // Check required fields
        if (empty($r['email']) || empty($r['username']) || empty($r['password'])) {
            echo "Error: Student email, username, and password are required to approve.";
            exit;
        }

        // Insert into users table (no 'status' field here, 'role' and 'created_at' fixed in query)
        $stmtUser = $conn->prepare("INSERT INTO users (
            username, full_name, dob, gender, contact, address, email, password,
            role, created_at, scholar_type, dormitory, student_id,
            admission_no, registration_no, stream, class, term, document_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'student', NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // 17 parameters
        $stmtUser->bind_param("sssssssssssssssss",
            $r['username'],
            $r['full_name'],
            $r['dob'],
            $r['gender'],
            $r['contact'],
            $r['address'],
            $r['email'],
            $r['password'],
            $r['scholar_type'],
            $r['dormitory'],
            $r['student_id'],
            $r['admission_no'],
            $r['registration_no'],
            $r['stream'],
            $r['class'],
            $r['term'],
            $r['document_path']
        );

        if ($stmtUser->execute()) {
            $user_id = $conn->insert_id;

            // Insert into student_list (with term and document_path)
            $stmtStudent = $conn->prepare("INSERT INTO student_list (
                user_id, class, stream, dormitory, dob, email, gender, address, contact,
                student_id, admission_no, registration_no, term, document_path,
                enrollment_date, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active')");

            $stmtStudent->bind_param("isssssssssssss",
                $user_id,
                $r['class'],
                $r['stream'],
                $r['dormitory'],
                $r['dob'],
                $r['email'],
                $r['gender'],
                $r['address'],
                $r['contact'],
                $r['student_id'],
                $r['admission_no'],
                $r['registration_no'],
                $r['term'],
                $r['document_path']
            );

            if ($stmtStudent->execute()) {
                // Delete from pending after successful approval
                $conn->query("DELETE FROM student_pending WHERE id = $id");
            } else {
                echo "Error inserting into student_list: " . $stmtStudent->error;
                exit;
            }

            $stmtStudent->close();
        } else {
            echo "Error inserting into users: " . $stmtUser->error;
            exit;
        }

        $stmtUser->close();
        header("Location: verify_students.php");
        exit;
    } else {
        echo "Student not found.";
        exit;
    }
}

// Handle student rejection
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("DELETE FROM student_pending WHERE id = $id");
    header("Location: verify_students.php");
    exit;
}

// Fetch all pending students
$res = $conn->query("SELECT * FROM student_pending ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Students</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        a { color: green; text-decoration: none; margin-right: 10px; }
        a.reject { color: red; }
        img.thumbnail { max-height: 60px; max-width: 60px; border: 1px solid #ccc; padding: 2px; background: #fff; }
    </style>
</head>
<body>
<h2>Pending Student Verifications</h2>

<?php if ($res && $res->num_rows > 0): ?>
<table>
    <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Email</th>
        <th>Class</th>
        <th>Stream</th>
        <th>Dormitory</th>
        <th>Student ID</th>
        <th>Admission No</th>
        <th>Registration No</th>
        <th>Term</th>
        <th>Document</th>
        <th>Submitted On</th>
        <th>Action</th>
    </tr>
    <?php while ($s = $res->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($s['username']) ?></td>
        <td><?= htmlspecialchars($s['full_name']) ?></td>
        <td><?= htmlspecialchars($s['dob']) ?></td>
        <td><?= htmlspecialchars($s['gender']) ?></td>
        <td><?= htmlspecialchars($s['contact']) ?></td>
        <td><?= htmlspecialchars($s['address']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['class']) ?></td>
        <td><?= htmlspecialchars($s['stream']) ?></td>
        <td><?= htmlspecialchars($s['dormitory']) ?></td>
        <td><?= htmlspecialchars($s['student_id']) ?></td>
        <td><?= htmlspecialchars($s['admission_no']) ?></td>
        <td><?= htmlspecialchars($s['registration_no']) ?></td>
        <td><?= htmlspecialchars($s['term']) ?></td>
        <td>
            <?php if (!empty($s['document_path'])): ?>
                <?php
                    $file = $s['document_path'];
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                ?>
                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <a href="<?= htmlspecialchars($file) ?>" target="_blank">
                        <img src="<?= htmlspecialchars($file) ?>" class="thumbnail" alt="Document Image" />
                    </a>
                <?php elseif ($ext === 'pdf'): ?>
                    <a href="<?= htmlspecialchars($file) ?>" target="_blank">📄 View PDF</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($file) ?>" target="_blank">📎 Open File</a>
                <?php endif; ?>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($s['created_at']) ?></td>
        <td>
            <a href="?approve=<?= $s['id'] ?>" onclick="return confirm('Approve this student?')">✅ Approve</a>
            <a href="?reject=<?= $s['id'] ?>" class="reject" onclick="return confirm('Reject this student?')">❌ Reject</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No pending students found.</p>
<?php endif; ?>

</body>
</html>
