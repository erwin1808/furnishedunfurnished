<?php 
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

session_start();

// Initialize messages
$errorMessage = '';
$successMessage = '';

// Check if we should show success toast from previous submission
$showSuccessToast = false;
if (isset($_SESSION['show_success_toast'])) {
    $showSuccessToast = true;
    unset($_SESSION['show_success_toast']); // Clear the flag so it only shows once
}

function generateTrackingNumber($conn) {
    $unique = false;
    $trackingNumber = '';
    
    while (!$unique) {
        // Generate a 13-digit number
        $trackingNumber = mt_rand(1000000000000, 9999999999999);
        
        // Check if it exists in the database
        $checkQuery = "SELECT tracking_number FROM inquiries WHERE tracking_number = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $trackingNumber);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 0) {
            $unique = true;
        }
        
        $stmt->close();
    }
    
    return $trackingNumber;
}

// Fetch user data if logged in
$userData = [];
if (isset($_SESSION['userid'])) {
    require '../includes/db.php';
    
    $userId = $_SESSION['userid'];
    $query = "SELECT first_name, last_name, email, office_phone, address, city, state, zip_code, return_address, return_city, return_state, return_zip_code, account_number FROM users WHERE userid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        // Combine first and last name
        $userData['full_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
        $userData['phone'] = $userData['office_phone']; // Map office_phone to phone
    }
    $stmt->close();
    $conn->close();
}

// Email processing when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user data - either from form or from session
    if (isset($_SESSION['userid']) && !empty($userData)) {
        // Use data from user table for logged-in users
        $name = $userData['full_name'];
        $email = $userData['email'];
        $phone = $userData['phone'];
        $account_number = $userData['account_number'];
    } else {
        // Use data from form for non-logged-in users
        $name = filter_var($_POST['client_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
        $account_number = '';
    }

    // Get address data - prioritize return address if available, otherwise use regular address
    $address = isset($_POST['return_address']) && !empty($_POST['return_address']) ? 
                filter_var($_POST['return_address'], FILTER_SANITIZE_STRING) : 
                (isset($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : 
                (isset($userData['address']) ? $userData['address'] : ''));
                
    $city = isset($_POST['return_city']) && !empty($_POST['return_city']) ? 
                filter_var($_POST['return_city'], FILTER_SANITIZE_STRING) : 
                (isset($_POST['city']) ? filter_var($_POST['city'], FILTER_SANITIZE_STRING) : 
                (isset($userData['city']) ? $userData['city'] : ''));
                
    $state = isset($_POST['return_state']) && !empty($_POST['return_state']) ? 
                filter_var($_POST['return_state'], FILTER_SANITIZE_STRING) : 
                (isset($_POST['state']) ? filter_var($_POST['state'], FILTER_SANITIZE_STRING) : 
                (isset($userData['state']) ? $userData['state'] : ''));
                
    $zip_code = isset($_POST['return_zip_code']) && !empty($_POST['return_zip_code']) ? 
                filter_var($_POST['return_zip_code'], FILTER_SANITIZE_STRING) : 
                (isset($_POST['zip_code']) ? filter_var($_POST['zip_code'], FILTER_SANITIZE_STRING) : 
                (isset($userData['zip_code']) ? $userData['zip_code'] : ''));

    // Get other form data
    $request_type = filter_var($_POST['request_type'], FILTER_SANITIZE_STRING);
    
    // Get equipment data
    $equipmentRequests = [];
    if (isset($_POST['equipment'])) {
        foreach ($_POST['equipment'] as $equip) {
            $equipmentRequests[] = [
                'description' => filter_var($equip['description'], FILTER_SANITIZE_STRING),
                'serial_number' => filter_var($equip['serial_number'], FILTER_SANITIZE_STRING),
                'issue_description' => filter_var($equip['issue_description'], FILTER_SANITIZE_STRING)
            ];
        }
    }

    // Validate required fields
    if (empty($name)) {
        $errorMessage = "Name is required";
    } elseif (empty($email)) {
        $errorMessage = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format";
    } elseif (empty($equipmentRequests)) {
        $errorMessage = "Please add at least one equipment item";
    } elseif (empty($request_type)) {
        $errorMessage = "Please select a request type";
    } elseif (empty($address) || empty($city) || empty($state) || empty($zip_code)) {
        $errorMessage = "Please fill in all address fields (either return address or primary address)";
    } else {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.ohiodentalrepair.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@ohiodentalrepair.com';
            $mail->Password = 'OHIODENT3rw!n';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            
            // Save to inquiries table
            require '../includes/db.php'; // Include your database connection
            
            if ($conn->connect_error) {
                throw new Exception("Database connection failed: " . $conn->connect_error);
            }

            // Generate tracking number before insertion
            $trackingNumber = generateTrackingNumber($conn);

            // Recipients
            $mail->setFrom('no-reply@ohiodentalrepair.com', 'Furnished Unfurnished');
            $mail->addAddress('erwinnrs31@gmail.com', 'Furnished Unfurnished');
            $mail->addReplyTo($email, $name);
            
            // Email content
            $mail->isHTML(true);
            $mail->Subject = $request_type  . " - ". $account_number;
            
            // Build equipment list HTML
            $equipmentListHtml = '';
            foreach ($equipmentRequests as $index => $equip) {
                $equipmentListHtml .= "
                <div style='margin-bottom: 20px; padding: 15px; background-color: #f0f0f0; border-radius: 5px;'>
                    <h4 style='margin-top: 0;'>Equipment #" . ($index + 1) . "</h4>
                    <p><strong>Description:</strong> " . htmlspecialchars($equip['description']) . "</p>
                    <p><strong>Serial Number:</strong> " . htmlspecialchars($equip['serial_number']) . "</p>
                    <p><strong>Diagnosis of Equipment Failure:</strong><br>" . nl2br(htmlspecialchars($equip['issue_description'])) . "</p>
                </div>";
            }
            
            // Build email body based on request type
            $emailBody = "
<html>
<head>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f4f4f4;
            padding: 30px;
        }
        .mail-container {
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .mail-header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .mail-content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .mail-footer {
            margin-top: 30px;
            font-size: 14px;
            color: #555;
            border-top: 1px dashed #aaa;
            padding-top: 15px;
        }
        .address-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .tracking-info {
            background-color: #e6f7ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            border-left: 4px solid #0ea5e9;
        }
        .tracking-number {
            font-size: 18px;
            font-weight: bold;
            color: #0ea5e9;
        }
        .equipment-item {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class='mail-container'>
        <div class='mail-header'>
            <h2>📦 New Request</h2>
        </div>
        <div class='mail-content'>
            <div class='tracking-info'>
                <p>Request Tracking Number:</p>
                <p class='tracking-number'>$trackingNumber</p>
                <p>Use this number to check the status of client's request.</p>
            </div>
            
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>";
            
            if (!empty($account_number)) {
                $emailBody .= "<p><strong>Account Number:</strong> $account_number</p>";
            }
            
            $emailBody .= "<p><strong>Request Type:</strong> $request_type</p>";
            
            $emailBody .= "
            <div class='address-section'>
                <p><strong>" . ($request_type == 'Pick Up' ? 'Pickup' : 'Return') . " Address:</strong></p>
                <p>Address: $address</p>
                <p>City: $city</p>
                <p>State(Zip): $state($zip_code)</p>
            </div>";
            
            $emailBody .= "
            <h3>Equipment Details</h3>
            $equipmentListHtml
    
        </div>
        <div class='mail-footer'>
            <p>This request was submitted via your website Request form.</p>
        </div>
    </div>
</body>
</html>";
            
            $mail->Body = $emailBody;

            // Send email
            $mail->send();
            
            // Insert into inquiries table
            $query = "INSERT INTO inquiries (
              client_name, 
              email, 
              phone_number, 
              request_type, 
              submittedAt, 
              status, 
              is_deleted,
              address,
              city,
              state,
              zip_code,
              account_number,
              tracking_number
          ) VALUES (?, ?, ?, ?, NOW(), 'Pending', 0, ?, ?, ?, ?, ?, ?)";
          
          $stmt = $conn->prepare($query);
          if (!$stmt) {
              throw new Exception("Prepare failed: " . $conn->error);
          }
          
          $bindResult = $stmt->bind_param(
              "ssssssssss", // 11 variables = 11 type specifiers
              $name, 
              $email, 
              $phone, 
              $request_type, 
              $address,
              $city,
              $state,
              $zip_code,
              $account_number,
              $trackingNumber
          );
          
            if (!$bindResult) {
                throw new Exception("Bind parameters failed: " . $stmt->error);
            }

            $executeResult = $stmt->execute();

            if ($executeResult) {
                // Insert equipment requests
                $equipInsertQuery = "INSERT INTO equipment_requests (
                    tracking_number,
                    description,
                    serial_number,
                    issue_description,
                    created_at
                ) VALUES (?, ?, ?, ?, NOW())";
                
                $equipStmt = $conn->prepare($equipInsertQuery);
                if (!$equipStmt) {
                    throw new Exception("Prepare failed for equipment: " . $conn->error);
                }
                
                foreach ($equipmentRequests as $equip) {
                    $equipStmt->bind_param(
                        "ssss",
                        $trackingNumber,
                        $equip['description'],
                        $equip['serial_number'],
                        $equip['issue_description']
                    );
                    
                    if (!$equipStmt->execute()) {
                        throw new Exception("Execute failed for equipment: " . $equipStmt->error);
                    }
                }
                
                $equipStmt->close();
                
                $_SESSION['show_success_toast'] = true;
                $_SESSION['tracking_number'] = $trackingNumber;
                $stmt->close();
                $conn->close();
                header("Location: request_user.php");
                exit();
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
        } catch (Exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
            error_log($e->getMessage());

            if (isset($stmt) && $stmt) $stmt->close();
            if (isset($conn) && $conn) $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Repair Request | Furnished Unfurnished</title>
    <?php include "../includes/icon.php"; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
  <style>
     /* Base Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #333;
      margin: 0;
      padding: 0;
    }
    
    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
    
    .section-title {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .section-title h2 {
      font-size: 2.2rem;
      color: #0ea5e9;
      margin-bottom: 15px;
    }
    
    .section-title p {
      font-size: 1.1rem;
      color: #666;
    }
    
    /* Contact Form Styles */
    .contact {
      padding: 80px 0;
      background-color: #ffffff;
    }
    
    .contact form {
      max-width: 700px;
      margin: 0 auto;
      background-color: #f4f6f8;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #444;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
      transition: border 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: #0ea5e9;
      outline: none;
    }
    
    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }
    
    .form-note {
      font-size: 0.9rem;
      color: #555;
      margin-top: -10px;
      margin-bottom: 10px;
    }
    
    /* Address section */
    #addressFields {
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
    }
    
    .address-row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -10px;
    }
    
    .address-col {
      flex: 1;
      padding: 0 10px;
      min-width: 200px;
    }
    
    .contact-info {
      text-align: center;
      margin-top: 40px;
    }
    
    .contact-info p {
      font-size: 1rem;
      color: #444;
      margin-bottom: 10px;
    }
    
    .cta-box {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 5px;
      text-align: center;
      margin-top: 40px;
    }
    
    /* Button Styles */
    .modern-button {
      padding: 14px 40px;
      background: linear-gradient(to right, #0ea5e9, #2563eb);
      border: none;
      border-radius: 30px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .modern-button::after {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: left 0.3s ease;
    }
    
    .modern-button:hover::after {
      left: 100%;
    }
    
    .modern-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Error message styling */
    .error-message {
      color: #d9534f;
      background-color: #f2dede;
      border: 1px solid #ebccd1;
      padding: 10px 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      display: none;
    }
    
    /* Required field indicator */
    .required-field {
      color: #FF7F7F;
      font-size: 12px;
    }
    
    /* Logged-in user notice */
    .logged-in-notice {
      background-color: #e6f7ff;
      border-left: 4px solid #0ea5e9;
      padding: 10px 15px;
      margin-bottom: 20px;
      border-radius: 4px;
      font-size: 0.9rem;
    }
    
    /* Hidden input fields for logged-in users */
    .hidden-user-data {
      display: none;
    }
    
    /* Equipment section styles */
    .equipment-section {
        margin: 20px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
    }
    
    .equipment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .equipment-list {
        margin-top: 20px;
    }
    
    .equipment-item {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        position: relative;
    }
    
    .remove-equipment {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ff6b6b;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
    }
    
    .add-equipment-btn {
        background-color: #0ea5e9;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .add-equipment-btn:hover {
        background-color: #0d8ecf;
    }
    
    /* Shipping box note styles */
    #shippingBoxNote {
        display: none;
        margin-top: 15px;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .download-button {
        background: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
        margin-top: 10px;
        font-weight: bold;
    }
    
    .download-button:hover {
        background: #45a049;
    }
  </style>
</head>
<body>

<!-- Header -->
<?php include "../includes/header.php" ?>

<!-- Contact Section -->
<section class="contact" id="contact">
  <div class="container">
    <div class="section-title" data-aos="fade-down">
      <h2>Repair Request Furnished Unfurnished</h2>
      <p>Have a request? Get in touch with our team today.</p>
    </div>

    <!-- Error message display -->
    <?php if(!empty($errorMessage)): ?>
      <div class="error-message" id="errorMessage" style="display: block;">
        <?php echo $errorMessage; ?>
      </div>
    <?php endif; ?>
    
    <!-- Logged in user notice -->
    <?php if(isset($_SESSION['userid']) && !empty($userData)): ?>
      <div class="logged-in-notice" data-aos="fade-up" style="display: none;">
        <p><i class="fas fa-info-circle"></i> You're logged in as <?php echo htmlspecialchars($userData['full_name']); ?>. Your account information will be used for this request.</p>
      </div>
    <?php endif; ?>

    <form id="requestForm" action="request_user.php" method="post" data-aos="fade-up">
      <?php if(!isset($_SESSION['userid']) || empty($userData)): ?>
        <!-- Show these fields only for non-logged-in users -->
        <div class="form-group">
          <label for="client_name">Full Name <span class="required-field">(Required)</span></label>
          <input type="text" id="client_name" name="client_name" required 
                 value="<?php echo isset($_POST['client_name']) ? htmlspecialchars($_POST['client_name']) : ''; ?>" />
        </div>

        <div class="form-group">
          <label for="email">Email Address <span class="required-field">(Required)</span></label>
          <input type="email" id="email" name="email" required 
                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>

        <div class="form-group">
          <label for="phone_number">Phone Number <span class="required-field">(Required)</span></label>
          <input type="tel" id="phone_number" name="phone_number" required 
                 value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" />
        </div>
      <?php else: ?>
        <!-- Hidden fields for logged-in users to pass the data -->
        <input type="hidden" name="client_name" value="<?php echo htmlspecialchars($userData['full_name']); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
        <input type="hidden" name="phone_number" value="<?php echo htmlspecialchars($userData['phone']); ?>">
      <?php endif; ?>

      <div class="form-group">
        <label for="request_type">Request Type <span class="required-field">(Required)</span></label>
        <select id="request_type" name="request_type" required>
            <option value="Pick Up" <?php echo (isset($_POST['request_type']) && $_POST['request_type'] == 'Pick Up') ? 'selected' : ''; ?>>Pick Up</option>
            <option value="Incoming Repair" <?php echo (isset($_POST['request_type']) && $_POST['request_type'] == 'Incoming Repair') ? 'selected' : ''; ?>>Mail-in</option>
        </select>
      </div>

 

      <!-- Equipment Section -->
      <div class="equipment-section">
        <div class="equipment-header">
          <h3>Equipment Details</h3>
          <button type="button" id="addEquipment" class="add-equipment-btn">
            <i class="fas fa-plus"></i> Add Equipment
          </button>
        </div>
        
        <div class="equipment-list" id="equipmentList">
          <!-- Equipment items will be added here -->
          <div class="equipment-item">
            <button type="button" class="remove-equipment" onclick="removeEquipment(this)">×</button>
            <div class="form-group">
              <label>Description <span class="required-field">(Required)</span></label>
              <input type="text" name="equipment[0][description]" required placeholder="Equipment description">
            </div>
            <div class="form-group">
              <label>Serial Number</label>
              <input type="text" name="equipment[0][serial_number]" placeholder="Equipment serial number">
            </div>
            <div class="form-group">
              <label>Diagnosis of Equipment Failure <span class="required-field">(Required)</span></label>
              <textarea name="equipment[0][issue_description]" required placeholder="Describe the issue with this equipment"></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Address fields (always visible) -->
   <div id="addressFields" style="display: none;">
  <h3 id="addressSectionTitle">Pickup / Return Address</h3>

  <!-- Street Address -->
  <div class="form-group">
    <label for="return_address">Street Address <span class="required-field">(Required)</span></label>
    <input type="text" id="return_address" name="return_address" required
      value="<?php 
        echo isset($_POST['return_address']) && $_POST['return_address'] !== '' 
          ? htmlspecialchars($_POST['return_address']) 
          : (
              !empty($userData['return_address']) 
                ? htmlspecialchars($userData['return_address']) 
                : (isset($userData['address']) ? htmlspecialchars($userData['address']) : '')
            ); 
      ?>">
  </div>

  <div class="address-row">
    <!-- City -->
    <div class="address-col">
      <div class="form-group">
        <label for="return_city">City <span class="required-field">(Required)</span></label>
        <input type="text" id="return_city" name="return_city" required
          value="<?php 
            echo isset($_POST['return_city']) && $_POST['return_city'] !== '' 
              ? htmlspecialchars($_POST['return_city']) 
              : (
                  !empty($userData['return_city']) 
                    ? htmlspecialchars($userData['return_city']) 
                    : (isset($userData['city']) ? htmlspecialchars($userData['city']) : '')
                ); 
          ?>">
      </div>
    </div>

    <!-- State -->
    <div class="address-col">
      <div class="form-group">
        <label for="return_state">State <span class="required-field">(Required)</span></label>
        <input type="text" id="return_state" name="return_state" required
          value="<?php 
            echo isset($_POST['return_state']) && $_POST['return_state'] !== '' 
              ? htmlspecialchars($_POST['return_state']) 
              : (
                  !empty($userData['return_state']) 
                    ? htmlspecialchars($userData['return_state']) 
                    : (isset($userData['state']) ? htmlspecialchars($userData['state']) : '')
                ); 
          ?>">
      </div>
    </div>

    <!-- ZIP Code -->
    <div class="address-col">
      <div class="form-group">
        <label for="return_zip_code">ZIP Code <span class="required-field">(Required)</span></label>
        <input type="text" id="return_zip_code" name="return_zip_code" required
          value="<?php 
            echo isset($_POST['return_zip_code']) && $_POST['return_zip_code'] !== '' 
              ? htmlspecialchars($_POST['return_zip_code']) 
              : (
                  !empty($userData['return_zip_code']) 
                    ? htmlspecialchars($userData['return_zip_code']) 
                    : (isset($userData['zip_code']) ? htmlspecialchars($userData['zip_code']) : '')
                ); 
          ?>">
      </div>
    </div>
  </div>
</div>


      <!-- Shipping Box Note (initially hidden) -->
      <div id="shippingBoxNote">
        <p><strong>Download Furnished Unfurnished Mailing Label and attach to your shipping package.</strong></p>
        
        <a href="../files/shipping_label.pdf" download class="download-button">
           Download Mailing Label
        </a>
        
  
      </div>

      <div class="form-group" style="text-align: center;">
        <button type="submit" class="modern-button">Submit Request</button>
      </div>
    </form>

    <div class="cta-box" data-aos="fade-up">
      <p>Need immediate assistance or not sure what you need? Email or call us — we're here to help!</p>
      <p><strong>Email:</strong> ohiodentalrepair@gmail.com</p>
      <p style="display: none;"><strong>Phone:</strong> (614) 306-9986</p>
      <a href="../services/services.php" class="modern-button" style="display: inline-block; margin-top: 15px;">
        Email Us Now
      </a>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include "../includes/foot.php"; ?>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
  
  // Hide error message after 5 seconds
  const errorMessage = document.getElementById('errorMessage');
  if (errorMessage) {
    setTimeout(() => {
      errorMessage.style.display = 'none';
    }, 5000);
  }
  
  // Equipment management
  let equipmentCount = 1;
  
  document.getElementById('addEquipment').addEventListener('click', function() {
      const equipmentList = document.getElementById('equipmentList');
      const newItem = document.createElement('div');
      newItem.className = 'equipment-item';
      newItem.innerHTML = `
          <button type="button" class="remove-equipment" onclick="removeEquipment(this)">×</button>
          <div class="form-group">
              <label>Description <span class="required-field">(Required)</span></label>
              <input type="text" name="equipment[${equipmentCount}][description]" required placeholder="Equipment description">
          </div>
          <div class="form-group">
              <label>Serial Number</label>
              <input type="text" name="equipment[${equipmentCount}][serial_number]" placeholder="Equipment serial number">
          </div>
          <div class="form-group">
              <label>Diagnosis of Equipment Failure <span class="required-field">(Required)</span></label>
              <textarea name="equipment[${equipmentCount}][issue_description]" required placeholder="Describe the issue with this equipment"></textarea>
          </div>
      `;
      equipmentList.appendChild(newItem);
      equipmentCount++;
  });
  
  function removeEquipment(button) {
      const equipmentList = document.getElementById('equipmentList');
      if (equipmentList.children.length > 1) {
          button.parentElement.remove();
      } else {
          alert('You must have at least one equipment item.');
      }
  }
  
  // Update address section title and shipping note based on request type
  document.getElementById('request_type').addEventListener('change', function() {
      const title = document.getElementById('addressSectionTitle');
      const shippingNote = document.getElementById('shippingBoxNote');
      
      if (this.value === 'Pick Up') {
          title.textContent = 'Pickup Address';
          shippingNote.style.display = 'none';
      } else {
          title.textContent = 'Return Address';
          shippingNote.style.display = 'block';
      }
  });
  
  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
      const requestType = document.getElementById('request_type');
      const shippingNote = document.getElementById('shippingBoxNote');
      
      // Show shipping note if Incoming Repair is already selected (form submission error case)
      if (requestType.value === 'Incoming Repair') {
          shippingNote.style.display = 'block';
      }
  });
  
  // Form validation to ensure at least one equipment item is filled out
  document.getElementById('requestForm').addEventListener('submit', function(e) {
      const equipmentItems = document.querySelectorAll('.equipment-item');
      let isValid = true;
      
      equipmentItems.forEach(item => {
          const description = item.querySelector('input[name*="[description]"]');
          const issue = item.querySelector('textarea[name*="[issue_description]"]');
          
          if (!description.value.trim() || !issue.value.trim()) {
              isValid = false;
              item.style.border = '1px solid red';
          } else {
              item.style.border = 'none';
          }
      });
      
      if (!isValid) {
          e.preventDefault();
          alert('Please fill out all required fields for each equipment item.');
      }
  });
</script>

<!-- SweetAlert Toast Notification -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success toast if flag is set
    <?php if($showSuccessToast): ?>
        const trackingNumber = '<?php echo isset($_SESSION['tracking_number']) ? $_SESSION['tracking_number'] : ''; ?>';
        
        Swal.fire({
            toast: true,
            position: 'top-bottom',
            icon: 'success',
            title: 'Your request has been submitted successfully!',
            html: trackingNumber ? `Your tracking number is: <strong>${trackingNumber}</strong><br>We've sent a confirmation email with details.` : 'Your request has been submitted!',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        <?php unset($_SESSION['tracking_number']); ?>
    <?php endif; ?>
    
    // Form submission with confirmation
    const contactForm = document.getElementById('requestForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirm Submission',
                html: 'Are you sure you want to submit this request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we submit your request',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    contactForm.submit();
                }
            });
        });
    }
});
</script>
</body>
</html>