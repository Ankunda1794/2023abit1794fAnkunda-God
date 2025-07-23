<?php
session_start();
if ($_SESSION['user']['role'] != 'student') die("Access Denied");

echo "Welcome, Student " . $_SESSION['user']['username'];
?>
