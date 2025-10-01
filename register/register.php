<?php
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include '../includes/db.php';

// Generate unique 8-digit numeric account number
function generateUniqueAccountNumber($conn) {
    do {
        // First 6 digits = current date in MMDDYY format
        $datePart = date('mdy'); // e.g., '062624'

        // Last 9 digits = random number from 000000000 to 999999999
        $randomPart = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);

        $account_number = $datePart . $randomPart;

        // Check if it already exists
        $stmt = $conn->prepare("SELECT 1 FROM users WHERE account_number = ?");
        $stmt->bind_param("s", $account_number);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    } while ($exists);

    return $account_number;
}



$emailExistsError = false;
function isValidPassword($password) {
    // Must contain only allowed characters: letters, numbers, @, #, _, &
    return preg_match('/^[A-Za-z0-9@#_&]+$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $practice_name = $_POST['practice_name'] ?? '';
    $practice_address = $_POST['practice_address'] ?? '';
    $address = $_POST['address'] ?? '';
    $return_address = $_POST['return_address'] ?? '';
    $return_city = $_POST['return_city'] ?? '';
    $return_state = $_POST['return_state'] ?? '';
    $return_zip_code = $_POST['return_zip_code'] ?? '';
    $office_days = isset($_POST['office_days']) ? implode(", ", $_POST['office_days']) : '';
    $office_hours = $_POST['office_hours'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $office_phone = $_POST['office_phone'] ?? '';
    $cell_phone = $_POST['cell_phone'] ?? '';
    $fax = $_POST['fax'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    
    if (!isValidPassword($password_raw)) {
        header("Location: register.php?status=error&message=" . urlencode("Password can only contain letters, numbers, and the following symbols: @, #, _, &"));
        exit;
    }

    // Check if email exists
    $checkStmt = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $emailExistsError = true;
    } else {
        $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);
        $account_number = generateUniqueAccountNumber($conn);

      $stmt = $conn->prepare("INSERT INTO users (
    first_name, last_name, practice_name, practice_address, address, city, state, 
    zip_code, return_address, return_city, return_state, return_zip_code, office_days, office_hours,
    office_phone, cell_phone, fax, email, password, account_number
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");


$stmt->bind_param("ssssssssssssssssssss", 
    $first_name, $last_name, $practice_name, $practice_address, $address,
    $city, $state, $zip_code, $return_address, $return_city, $return_state, $return_zip_code, 
    $office_days, $office_hours, // Now this will be a string like "Mon, Tue, Wed"
    $office_phone, $cell_phone, $fax, $email, $hashed_password, $account_number
);


        if ($stmt->execute()) {

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.ohiodentalrepair.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@ohiodentalrepair.com';
    $mail->Password = 'OHIODENT3rw!n';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('no-reply@ohiodentalrepair.com', 'Furnished Unfurnished');
    $mail->addAddress($email, $first_name . ' ' . $last_name);

    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Furnished Unfurnished';

    $mail->Body = "
        <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4;'>
            <div style='background-color: #ffffff; padding: 20px;'>
                <h2 style='color: #3a1450; text-align: center;'>Furnished Unfurnished</h2>

                <p>Hi <strong>$first_name $last_name</strong>,</p>
                <p>
                    Thank you for registering with Furnished Unfurnished. Here are your account details:
                </p>
                <p style='background-color: #f0f4f8; padding: 10px; font-size: 16px; font-weight: bold;'>
                    Email: $email<br>  
                    Temporary Password: $password_raw<br>
                    Account Number: $account_number
                </p>
                <p>If you have any questions or need help accessing your account, feel free to reach out to us using the contact details below.</p>

                <hr style='margin: 20px 0;'>

                <h4 style='color: #3a1450;'>Contact Info</h4>
                <p>
                    Furnished Unfurnished<br>
                    1967 Lockbourne Road Suite B<br>
                    Columbus, Ohio 43207
                </p>
                <p>
                    <strong>Phone:</strong> (614) 306-9986<br>
                    <strong>Email:</strong> 
                    <a href='mailto:ohiodentalrepair@gmail.com' style='color: #1a73e8; text-decoration: none;'>
                        ohiodentalrepair@gmail.com
                    </a><br>
                    <strong>Web:</strong> 
                    <a href='https://ohiodentalrepair.com' target='_blank' style='color: #1a73e8; text-decoration: none;'>
                        ohiodentalrepair.com
                    </a>
                </p>

                <p>
                    <strong>Follow Us:</strong> 
                    <a href='https://www.facebook.com/ohiodentalrepair' style='color: #1877F2; text-decoration: none;'>Facebook</a>
                </p>
            </div>
        </div>
    ";

    $mail->send();
    $emailSent = true;
} catch (Exception $e) {
    error_log("Welcome email failed to send. Error: " . $mail->ErrorInfo);
    $emailSent = false;
}




            header("Location: register.php?status=success");
            exit;
        } else {
            header("Location: register.php?status=error&message=" . urlencode($stmt->error));
            exit;
        }
    }
    $checkStmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register | Furnished Unfurnished</title>
<?php include "../includes/icon.php"; ?>
<link rel="stylesheet" href="styles.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

<style>
    /* Example CSS styling */
    :root {
        --dark-gray: #444;
        --primary-color: #007bff;
        --error-color: #e53935;
        --success-color: #28a745;
    }

    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .logo-company-name {
        padding: 1rem;
        background: #0056b3;
        color: #fff;
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .container {
        max-width: 600px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        padding: 2rem;
    }

    h2.form-title {
        margin-bottom: 1rem;
        text-align: center;
        color: var(--primary-color);
    }

    form .form-group {
        margin-bottom: 1rem;
    }

    label {
        display: block;
        margin-bottom: .3rem;
        font-weight: 600;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 8px 12px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .required {
        color: var(--error-color);
    }

    .form-section {
        margin-bottom: 2rem;
        border-top: 1px solid #ddd;
        padding-top: 1rem;
    }

    .section-title {
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .form-group-row {
        display: flex;
        gap: 1rem;
    }

    .form-group-row .form-group {
        flex: 1;
    }

    .submit-btn {
        background: var(--primary-color);
        border: none;
        color: white;
        padding: 12px 20px;
        font-size: 1.1rem;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .submit-btn:hover {
        background: #0056b3;
    }

    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg);}
        100% { transform: rotate(360deg);}
    }

    .password-strength {
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        margin-top: 5px;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        background-color: var(--error-color);
        transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--dark-gray);
    }

    .login-link {
        margin-top: 1rem;
        text-align: center;
    }

    .login-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="logo-company-name">Furnished Unfurnished</div>

<div class="container">
    <h2 class="form-title">Create Your Account</h2>

<form method="POST" id="registrationForm" novalidate>
    <!-- Primary Contact Section -->
    <div class="form-section">
        <h3 class="section-title">Primary Contact</h3>

        <div class="form-group-row">
            <div class="form-group">
                <label for="first_name">First Name <span class="required">*</span></label>
                <input type="text" id="first_name" name="first_name" required />
            </div>
            <div class="form-group">
                <label for="last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="last_name" name="last_name" required />
            </div>
        </div>

        <div class="form-group">
            <label for="practice_name">Practice Name</label>
            <input type="text" id="practice_name" name="practice_name" />
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="practice_address">Practice Address</label>
                <input type="text" id="practice_address" name="practice_address" />
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" />
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" />
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" />
            </div>
            <div class="form-group">
                <label for="zip_code">Zip Code</label>
                <input type="text" id="zip_code" name="zip_code" />
            </div>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="office_phone">Office Phone</label>
                <input type="text" id="office_phone" name="office_phone" />
            </div>
            <div class="form-group">
                <label for="cell_phone">Cell Phone</label>
                <input type="text" id="cell_phone" name="cell_phone" />
            </div>
            <div class="form-group">
                <label for="fax">Fax</label>
                <input type="text" id="fax" name="fax" />
            </div>
        </div>
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Office Days Dropdown -->
<div class="form-group">
    <label for="office_days">Office Days (e.g. Mon–Fri):</label>
  <select id="office_days" name="office_days[]" class="form-control select2" multiple="multiple">
    <option value="Mon">Monday</option>
    <option value="Tue">Tuesday</option>
    <option value="Wed">Wednesday</option>
    <option value="Thu">Thursday</option>
    <option value="Fri">Friday</option>
    <option value="Sat">Saturday</option>
    <option value="Sun">Sunday</option>
</select>
</div>

<!-- Include jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        $('#office_days').select2({
            placeholder: "Select office days",
            allowClear: true,
            width: '100%' // ensures it fits within form-control styling
        });
    });
</script>


<div class="form-group">
    <label for="office_hours">Office Hours (e.g. 9 AM – 5 PM):</label>
    <input type="text" id="office_hours" name="office_hours" placeholder="e.g. 9 AM – 5 PM" />
</div>

    </div>

    <!-- Return Address Section -->
    <div class="form-section">
        <h3 class="section-title">Return Address</h3>

        <div class="form-group">
            <label>
                <input type="checkbox" id="same_address" />
                Check if Return Address is the same as above
            </label>
        </div>

        <div id="return_address_fields">
            <hr />

            <div class="form-group">
                <label for="return_address">Address</label>
                <input type="text" id="return_address" name="return_address" />
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="return_city">City</label>
                    <input type="text" id="return_city" name="return_city" />
                </div>
                <div class="form-group">
                    <label for="return_state">State</label>
                    <input type="text" id="return_state" name="return_state" />
                </div>
                <div class="form-group">
                    <label for="return_zip_code">Zip Code</label>
                    <input type="text" id="return_zip_code" name="return_zip_code" />
                </div>
            </div>

            <hr />
        </div>
    </div>

    <!-- Script to Copy Address -->
    <script>
        document.getElementById('same_address').addEventListener('change', function () {
            const isChecked = this.checked;
            const returnFields = document.getElementById('return_address_fields');

            if (isChecked) {
                document.getElementById('return_address').value = document.getElementById('address').value;
                document.getElementById('return_city').value = document.getElementById('city').value;
                document.getElementById('return_state').value = document.getElementById('state').value;
                document.getElementById('return_zip_code').value = document.getElementById('zip_code').value;
                returnFields.style.display = 'none';
            } else {
                document.getElementById('return_address').value = '';
                document.getElementById('return_city').value = '';
                document.getElementById('return_state').value = '';
                document.getElementById('return_zip_code').value = '';
                returnFields.style.display = '';
            }
        });
    </script>

    <!-- Login Credentials Section -->
    <div class="form-section">
        <h3 class="section-title">Login Credentials</h3>

        <p style="margin-bottom: 15px; font-size: 14px; color: var(--dark-gray);">
            The below information is needed for signing into your account now and in the future. Please retain this information.
        </p>

        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" required />
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required />
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
                <small class="form-text">Uppercase, Lowercase, Numbers, Special Characters (@, #, _, &)</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" required />
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="submit-btn">
        <span id="submitText">Create Account</span>
        <span id="submitSpinner" class="spinner" style="display: none;"></span>
    </button>
</form>



    <p class="login-link">
        Already have an account? <a href="../login/index.php">Log in here</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Password strength bar logic
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('passwordStrengthBar');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        const percent = (strength / 5) * 100;
        strengthBar.style.width = percent + '%';

        if (percent < 40) {
            strengthBar.style.backgroundColor = '#ff6b6b'; // red
        } else if (percent < 70) {
            strengthBar.style.backgroundColor = '#ffc107'; // yellow
        } else {
            strengthBar.style.backgroundColor = '#28a745'; // green
        }
    });

    // Form submission confirmation and validation
    const form = document.getElementById('registrationForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Simple client-side validation for password confirmation
        if (passwordInput.value !== confirmPasswordInput.value) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!',
                confirmButtonColor: '#e53935'
            });
            return;
        }

        Swal.fire({
            title: 'Confirm Registration',
            text: 'Are you sure all information is correct?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, register!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show spinner and disable submit button
                document.getElementById('submitText').style.display = 'none';
                document.getElementById('submitSpinner').style.display = 'inline-block';

                form.submit();
            }
        });
    });
});

// Show SweetAlert on success or error from URL params
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');
    const message = params.get('message');

    if (status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: 'Your account has been created successfully!',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = '../login/index.php';
        });
    } else if (status === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message || 'An error occurred. Please try again.',
            confirmButtonColor: '#e53935'
        });
    }
});

// Show alert if email already exists (set by PHP variable)
<?php if ($emailExistsError): ?>
Swal.fire({
    icon: 'error',
    title: 'Email Exists',
    text: 'This email is already registered. Please use a different email.',
    confirmButtonColor: '#e53935'
});
<?php endif; ?>
</script>

</body>
</html>
