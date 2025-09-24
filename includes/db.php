<?php
// Database connection (adjust with your credentials)
$host = 'localhost';
$db = 'ohiodentalrepair';
$user = 'root';
$pass = '';
    

// Create MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



// Create connection
$conn = new mysqli($host, $user, $pass, $db);



