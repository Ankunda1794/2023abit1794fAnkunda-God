<?php
session_start();
require 'config.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<h2>Access Denied</h2><p><a href='login.php'>Login</a></p>";
    exit();
}

$username = $_SESSION['username'];

// ✅ Fetch role and position from DB
$stmt = $conn->prepare("SELECT role, position FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2>User Not Found</h2>";
    exit();
}

$user = $result->fetch_assoc();

// ✅ Only Headteacher is allowed
if (strtolower(trim($user['position'])) !== 'headteacher') {
    echo "<h2>Access Denied</h2><p>Only Headteachers can view documents.</p>";
    exit();
}

// ✅ Fetch documents only submitted by users with position 'secretary'
$docsQuery = $conn->query("
    SELECT d.*, u.position 
    FROM documents d
    JOIN users u ON d.created_by = u.username
    WHERE LOWER(u.position) = 'secretary'
    ORDER BY d.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headteacher - View Documents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
            text-align: left;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .download-btn {
            background-color: #28a745;
            color: #fff;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .download-btn:hover {
            background-color: #218838;
        }
        em {
            color: red;
        }
    </style>
</head>
<body>

<h2>📄 Documents Submitted by Secretaries</h2>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Submitted By</th>
            <th>Status</th>
            <th>Date</th>
            <th>Download</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($docsQuery && $docsQuery->num_rows > 0): ?>
            <?php while ($row = $docsQuery->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['doc_type']) ?></td>
                    <td><?= htmlspecialchars($row['created_by']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                    <td>
                        <?php
                        $filePath = "uploads/" . basename($row['filename']);
                        if (!empty($row['filename']) && file_exists($filePath)): ?>
                            <a class="download-btn" href="<?= $filePath ?>" download>Download</a>
                        <?php else: ?>
                            <em>Not Found</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align: center;">No documents found from secretaries.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
