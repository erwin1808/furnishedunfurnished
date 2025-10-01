<?php
// functions/send_otp.php
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $otp = rand(100000, 999999); // 6-digit OTP

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    try {
        // ✅ Check if user exists and is already verified
        $stmtCheck = $conn->prepare("SELECT id, account_number, otp_verified FROM users WHERE email=?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        
        if ($stmtCheck->num_rows > 0) {
            // User exists, check verification status
            $stmtCheck->bind_result($user_id, $account_number, $otp_verified);
            $stmtCheck->fetch();
            $stmtCheck->close();
            
            // If user is already verified, redirect to step-1 immediately
            if ($otp_verified == 1) {
                echo json_encode([
                    "status" => "redirect", 
                    "redirect" => "step-1.php?account_number=" . $account_number
                ]);
                exit;
            }
            
            // User exists but not verified, use existing user_id
        } else {
            // New user - generate account_number
            $yearPrefix = date("Y");
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

            // Insert new user
            $stmtInsert = $conn->prepare("INSERT INTO users (email, account_number, otp_verified) VALUES (?, ?, 0)");
            $stmtInsert->bind_param("ss", $email, $account_number);
            $stmtInsert->execute();
            $user_id = $stmtInsert->insert_id;
            $stmtInsert->close();
        }

        // ✅ Insert OTP into otp_codes
        $expires_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $stmtOtp = $conn->prepare("INSERT INTO otp_codes (user_id, otp_code, purpose, expires_at) VALUES (?, ?, 'registration', ?)");
        $stmtOtp->bind_param("iss", $user_id, $otp, $expires_at);
        $stmtOtp->execute();
        $stmtOtp->close();

        // ✅ Send email with PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'erwinnares1@gmail.com'; // your Gmail
        $mail->Password   = 'jwtj zkms bdof qtex';  // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Sender must match the authenticated Gmail account
        $mail->setFrom('erwinnares1@gmail.com', 'Ohio Dental Repair');
        $mail->addAddress($email); // recipient from form

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<p>Your One-Time Password is:</p>
                          <h2 style='color:#00524e;'>{$otp}</h2>
                          <p>This code expires in 5 minutes.</p>";

        $mail->send();
        
        echo json_encode(["status" => "success", "message" => "OTP sent successfully!"]);
        
    } catch (Exception $e) {
        error_log("Send OTP Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Failed to send OTP. Please try again."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>