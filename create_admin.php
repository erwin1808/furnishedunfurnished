<?php
include 'includes/db.php';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $stmt->close();

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate random account number (example: 8 digits)
            $account_number = rand(10000000, 99999999);

            // Insert admin user
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, otp_verified, phone, user_type, created_at, updated_at, account_number, password, is_deleted) VALUES (?, ?, ?, 1, ?, 'admin', NOW(), NOW(), ?, ?, 0)");
          $stmt->bind_param("ssssis", $first_name, $last_name, $email, $phone, $account_number, $hashed_password);

            
            if ($stmt->execute()) {
                $success = "Admin account created successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin Account</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        form { max-width: 400px; margin: auto; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Create Admin Account</h2>

    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post" action="">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Admin</button>
    </form>
</body>
</html>
