<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $terms      = isset($_POST['terms']) ? 1 : 0;

    // Validation
    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name))  $errors[] = "Last name is required";
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

    // ✅ Generate Account Number (Retry System)
    $maxRetries = 5;
    $success = false;
    $account_number = null;

    for ($i = 0; $i < $maxRetries; $i++) {
        $yearPrefix = date("Y");

        // Get the latest account_number for this year
        $result = $conn->query("SELECT account_number 
                                FROM users 
                                WHERE account_number LIKE '{$yearPrefix}%' 
                                ORDER BY account_number DESC 
                                LIMIT 1");

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastNumber = (int)substr($row['account_number'], 4);
            $nextNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $nextNumber = "0001";
        }

        $account_number = $yearPrefix . $nextNumber;

        // Try inserting
        $stmt = $conn->prepare("INSERT INTO users 
            (first_name, last_name, email, phone, account_number, user_type, created_at) 
            VALUES (?, ?, ?, ?, ?, 'tenant', NOW())");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $account_number);

        if ($stmt->execute()) {
            $success = true;
            echo json_encode([
                'status' => 'success',
                'message' => 'Account created successfully!',
                'account_number' => $account_number
            ]);
            break;
        } else {
            if ($conn->errno == 1062) { 
                // Duplicate key error → regenerate account_number and retry
                continue;
            } else {
                // Other error
                echo json_encode(['status' => 'error', 'message' => 'Failed: ' . $conn->error]);
                break;
            }
        }
        $stmt->close();
    }

    if (!$success) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Could not generate a unique account number. Please try again.'
        ]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
