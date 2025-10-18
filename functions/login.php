<?php
// functions/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();}
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit;
    }

// Check if user exists
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, user_type, password, account_number FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['account_number'] = $user['account_number']; // <-- Add this

        // Determine redirect URL
        $redirectUrl = match ($user['user_type']) {
            'admin' => 'admin/dashboard.php',
            'tenant' => 'index.php',
            'landlord' => 'landlord/dashboard.php',
            default => 'index.php'
        };

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
            'redirect' => $redirectUrl,
            'user' => [
                'id' => $user['id'],
                'name' => $_SESSION['user_name'],
                'email' => $user['email'],
                'type' => $user['user_type'],
                'account_number' => $_SESSION['account_number'] // optional for frontend
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}

    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
