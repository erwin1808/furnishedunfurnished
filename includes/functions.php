<?php
// functions.php
require 'db.php';

function authenticateUser($email, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT userid, email, roles, first_name, last_name, account_number, password FROM users WHERE email = ? AND is_deleted = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

