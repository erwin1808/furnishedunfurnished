<?php
// includes\session_check.php
ob_start();
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit();
}

$userid = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

?>
