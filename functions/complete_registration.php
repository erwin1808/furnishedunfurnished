<?php
// furnishedunfurnished/functions/complete_registration.php
session_start();
include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);

    // Validate input
    if (empty($email) || empty($first_name) || empty($last_name) || empty($phone)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Capitalize the first letter of each name
    $first_name = ucwords(strtolower($first_name));
    $last_name = ucwords(strtolower($last_name));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    try {
        // Get account_number from session (set during OTP verification)
        if (!isset($_SESSION['account_number'])) {
            echo json_encode(["status" => "error", "message" => "Session expired. Please start over."]);
            exit;
        }

        $account_number = $_SESSION['account_number'];

        // Update user details in the database
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, phone=? WHERE account_number=? AND email=?");
        $stmt->bind_param("sssss", $first_name, $last_name, $phone, $account_number, $email);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Success - redirect to structure.php with account_number
                echo json_encode([
                    "status" => "success", 
                    "message" => "Registration completed successfully!", 
                  "redirect" => "structure.php?an=" . urlencode($account_number)
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "No changes made or user not found"]);
            }
        } else {
            throw new Exception("Database update failed");
        }

        $stmt->close();

    } catch (Exception $e) {
        error_log("Complete Registration Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "System error. Please try again"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method or missing parameters"]);
}

if (isset($conn)) {
    $conn->close();
}
?>
