<?php
require 'config.php';

$errors = [];
$success = "";
$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid request.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$new_password || !$confirm_password) {
        $errors[] = "Both password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($reset = $result->fetch_assoc()) {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $user_id = $reset['user_id'];

            $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt2->bind_param("si", $hash, $user_id);
            $stmt2->execute();

            $conn->query("DELETE FROM password_resets WHERE user_id = $user_id");

            $success = "Password has been reset. <a href='login.php'>Login now</a>";
        } else {
            $errors[] = "Invalid or expired token.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; display: flex; justify-content: center; padding-top: 80px; }
        form { background: #fff; padding: 30px; width: 350px; box-shadow: 0 0 10px #ccc; border-radius: 8px; }
        input, button { width: 100%; padding: 10px; margin-top: 10px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<form method="POST">
    <h2>Reset Password</h2>
    <?php if ($errors): ?><div class="error"><?php foreach ($errors as $e) echo "<p>$e</p>"; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Reset Password</button>
</form>

</body>
</html>
