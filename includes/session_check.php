<?php
ob_start();
session_start();

if (!isset($_SESSION['userid']) || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit();
}

// Store session variables (optional)
$userid = $_SESSION['userid'];
$user_type = $_SESSION['user_type'];
?>
