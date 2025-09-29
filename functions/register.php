<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Validation
    $errors = [];

    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (!$terms) $errors[] = "You must agree to the terms";

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
        exit;
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit;
    }
    $check_email->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, user_type, created_at) VALUES (?, ?, ?, ?, 'tenant', NOW())");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create account: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>