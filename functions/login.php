<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required']);
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_type'] = $user['user_type'];
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login successful!',
            'user' => $user
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>