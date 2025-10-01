<?php
session_start(); // Start the session at the very top
include "../includes/db.php";

// Enable error reporting for debugging (remove in production)
error_reporting(0); // Keep disabled in production

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp']) && isset($_POST['email'])) {
    $otp = trim($_POST['otp']);
    $email = trim($_POST['email']);

    // Validate input
    if (empty($otp) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Email and OTP are required"]);
        exit;
    }

    // Validate OTP format (6-digit number)
    if (!preg_match('/^\d{6}$/', $otp)) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP format. Must be 6 digits"]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    try {
        // Find user by email with account_number
        $stmtUser = $conn->prepare("SELECT id, account_number, otp_verified FROM users WHERE email=?");
        $stmtUser->bind_param("s", $email);
        $stmtUser->execute();
        $stmtUser->store_result();

        if ($stmtUser->num_rows === 0) {
            echo json_encode(["status" => "error", "message" => "User not found"]);
            exit;
        }

        $stmtUser->bind_result($user_id, $account_number, $otp_verified);
        $stmtUser->fetch();
        $stmtUser->close();

        // Validate OTP - get the latest unused OTP for this user
        $stmtOtp = $conn->prepare("SELECT id, expires_at, is_used, created_at 
                                   FROM otp_codes 
                                   WHERE user_id=? AND otp_code=? AND purpose='registration' 
                                   ORDER BY created_at DESC LIMIT 1");
        $stmtOtp->bind_param("is", $user_id, $otp);
        $stmtOtp->execute();
        $resultOtp = $stmtOtp->get_result();

        if ($row = $resultOtp->fetch_assoc()) {
            if (strtotime($row['expires_at']) < time()) {
                echo json_encode(["status" => "error", "message" => "OTP has expired. Please request a new one"]);
                exit;
            }

            $conn->begin_transaction();
            try {
                // Mark OTP as used
                $stmtUpdate = $conn->prepare("UPDATE otp_codes SET is_used=1 WHERE id=?");
                $stmtUpdate->bind_param("i", $row['id']);
                $stmtUpdate->execute();

                // Update user's otp_verified status to 1
                $stmtVerifyUser = $conn->prepare("UPDATE users SET otp_verified=1 WHERE id=?");
                $stmtVerifyUser->bind_param("i", $user_id);
                $stmtVerifyUser->execute();

                // Expire other unused OTPs
                $stmtExpireOthers = $conn->prepare("UPDATE otp_codes 
                                                   SET is_used=1 
                                                   WHERE user_id=? AND purpose='registration' AND is_used=0");
                $stmtExpireOthers->bind_param("i", $user_id);
                $stmtExpireOthers->execute();

                $conn->commit();

                // ✅ Store account_number in session
                $_SESSION['account_number'] = $account_number;

                echo json_encode([
                    "status" => "success", 
                    "message" => "OTP verified successfully!", 
                    "redirect" => "become-a-landlord.php"
                ]);

            } catch (Exception $e) {
                $conn->rollback();
                throw new Exception("Failed to update OTP status: " . $e->getMessage());
            }

        } else {
            echo json_encode(["status" => "error", "message" => "Invalid OTP code"]);
        }

        $stmtOtp->close();

    } catch (Exception $e) {
        error_log("OTP Verification Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "System error. Please try again"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method or missing parameters"]);
}

if (isset($conn)) {
    $conn->close();
}
?>
