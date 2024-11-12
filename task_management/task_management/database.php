<?php
$servername = "localhost";
$username = "root";
$password = ""; // Use the password set in your MySQL, usually blank for XAMPP
$dbname = "task_manager";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
