<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

// Include PHPMailer
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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

        // ✅ Generate Temporary Password
        $temp_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'), 0, 10);
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

        // Try inserting
        $stmt = $conn->prepare("INSERT INTO users 
            (first_name, last_name, email, phone, account_number, password, user_type, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'tenant', NOW())");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $account_number, $hashed_password);

        if ($stmt->execute()) {
            $success = true;

            // ✅ Send Email Notification
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'erwinnares1@gmail.com'; // your Gmail
                $mail->Password   = 'jwtj zkms bdof qtex';  // App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('erwinnares1@gmail.com', 'Furnished Unfurnished');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your Temporary Login Details';
                $mail->Body    = "
                    <p>Hello <strong>{$first_name} {$last_name}</strong>,</p>
                    <p>Welcome to <strong>Furnished Unfurnished!</strong> 🎉</p>
                    <p>Your account has been successfully created.</p>
                    <p><b>Account Number:</b> {$account_number}</p>
                    <p><b>Temporary Password:</b> {$temp_password}</p>
                    <p>Please log in and change your password immediately for security purposes.</p>
                    <br>
                    <p style='color:#777;'>Thank you,<br>Furnished Unfurnished Team</p>
                ";
            // Force a new thread by adding unique headers
            $mail->MessageID = '<' . uniqid() . '@furnishedunfurnished.com>';
            $mail->addCustomHeader('X-Mailer', 'FurnishedUnfurnishedMailer');
            $mail->addCustomHeader('X-Entity-Ref-ID', uniqid());
            $mail->addCustomHeader('In-Reply-To', '');
            $mail->addCustomHeader('References', '');
            $mail->addCustomHeader('Auto-Submitted', 'no');

                $mail->send();

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Account created successfully! Temporary password sent to email.',
                    'account_number' => $account_number
                ]);

            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'warning',
                    'message' => 'Account created but email not sent: ' . $mail->ErrorInfo,
                    'account_number' => $account_number
                ]);
            }

            break;
        } else {
            if ($conn->errno == 1062) { 
                // Duplicate key → retry
                continue;
            } else {
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
