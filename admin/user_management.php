<?php
//user_management.php
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
header('Surrogate-Control: no-store');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header('Location: ../login/index.php');
    exit();
}

// Database connection 
include '../includes/db.php';
include '../includes/functions.php';

function generateUniqueAccountNumber($conn) {
    do {
        $account_number = mt_rand(10000000, 99999999);
        $stmt = $conn->prepare("SELECT 1 FROM users WHERE account_number = ?");
        $stmt->bind_param("s", $account_number);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    } while ($exists);
    return $account_number;
}

// Fetch users for DataTable
$sql = "
SELECT 
 u.userid, 
 u.date_created, 
 u.account_number, 
 u.email, 
 u.roles, 
 u.office_phone, 
 u.cell_phone,
 u.office_hours,
 u.office_days,
 u.fax, 
 u.first_name, 
 u.last_name, 
 u.image,
 u.practice_name,
 u.practice_address,
 u.address,
 u.city,
 u.state,
 u.zip_code,
 u.return_address,
 u.return_city,
 u.return_state,
 u.return_zip_code
FROM users u
WHERE u.is_deleted = 0
ORDER BY u.date_created DESC;
";

$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
$users[] = [
    'userid' => $row['userid'],
    'date_created' => $row['date_created'], 
    'account_number' => $row['account_number'], 
    'email' => $row['email'],
    'roles' => $row['roles'],
    'office_phone' => $row['office_phone'],
     'cell_phone' => $row['cell_phone'],
    'office_hours' => $row['office_hours'],
    'fax' => $row['fax'],
    'first_name' => $row['first_name'],
    'last_name' => $row['last_name'],
    'image' => $row['image'] ? '../images/' . $row['image'] : '../images/undraw_profile.svg',
    'practice_name' => $row['practice_name'],
    'practice_address' => $row['practice_address'],
    'address' => $row['address'],
    'city' => $row['city'],
    'state' => $row['state'],
    'zip_code' => $row['zip_code'],
    'return_address' => $row['return_address'],
    'return_city' => $row['return_city'],
    'return_state' => $row['return_state'],
    'return_zip_code' => $row['return_zip_code']
];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $practice_name = $_POST['practice_name'] ?? '';
    $practice_address = $_POST['practice_address'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $office_phone = $_POST['office_phone'] ?? '';
    $fax = $_POST['fax'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $roles = $_POST['roles'] ?? 'user';
    $return_address = $_POST['return_address'] ?? '';
    $return_city = $_POST['return_city'] ?? '';
    $return_state = $_POST['return_state'] ?? '';
    $return_zip_code = $_POST['return_zip_code'] ?? '';
    $office_hours = $_POST['office_hours'] ?? '';
    $cell_phone = $_POST['cell_phone'] ?? '';

    // Check if email exists
    $checkStmt = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // For AJAX requests, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            exit;
        } else {
            $emailExistsError = true;
        }
    } else {
        $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);
        $account_number = generateUniqueAccountNumber($conn);

          $stmt = $conn->prepare("INSERT INTO users ( first_name, last_name, practice_name, practice_address, address, city,  state, zip_code, office_phone,
                                                                 cell_phone, office_hours, fax, email, password, account_number, roles, return_address, return_city,
                                                                 return_state, return_zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"    );

    $stmt->bind_param("ssssssssssssssssssss", 
    $first_name, $last_name, $practice_name, $practice_address, $address,
    $city, $state, $zip_code, $office_phone, $cell_phone, $office_hours, $fax, $email, $hashed_password, 
    $account_number, $roles, $return_address, $return_city, $return_state, $return_zip_code
);

        if ($stmt->execute()) {

$mail = new PHPMailer(true);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'mail.ohiodentalrepair.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@ohiodentalrepair.com';
    $mail->Password = 'OHIODENT3rw!n';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('no-reply@ohiodentalrepair.com', 'Ohio Dental Repair');
    $mail->addAddress($email, $first_name . ' ' . $last_name);

    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Ohio Dental Repair';
$mail->Body = "
    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4;'>
        <div style='background-color: #ffffff; padding: 20px;'>
            <h2 style='color: #3a1450; text-align: center;'>Ohio Dental Repair</h2>

            <p>Hi <strong>$first_name $last_name</strong>,</p>
            <p>
                Thank you for registering with Ohio Dental Repair. Here are your account details:
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
                Ohio Dental Repair<br>
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
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    $emailSent = false;
}

// For AJAX requests, return JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'email_sent' => $emailSent,
        'email_error' => $emailSent ? '' : 'Welcome email could not be sent. Please notify the user manually.'
    ]);
    exit;
} else {
                header("Location: user_management.php?status=success");
                exit;
            }
        } else {
            // For AJAX requests, return JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                exit;
            } else {
                header("Location: user_management.php?status=error&message=" . urlencode($stmt->error));
                exit;
            }
        }
    }
    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>User Management</title>
    <?php include "../includes/icon.php"; ?>
    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- DataTables Plugin -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome 6.6.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
          <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <style>
        .swal2-popup {
            background: linear-gradient(to top, #ffffff, #ffffff, #ffffff, #bad8eb);
        }

        .edit-user-input {
            margin-bottom: 12px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            max-width: 100%;
        }

        .edit-user-input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .edit-user-form-group {
            margin-bottom: 10px;
        }

        .edit-user-form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            display: block;
        }

        .edit-user-input-group {
            display: flex;
            align-items: center;
        }

        .edit-user-input-group-append {
            cursor: pointer;
            margin-left: -35px;
        }

        .edit-user-input-group-text {
            background-color: #fff;
            border-left: none;
        }

        .edit-user-popup {
            width: 600px;
        }

        .badge-large {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .badge-primary {
            color: #fff;
        }

        .badge-success {
            color: #fff;
        }

        #editUserModal label,
        #addUserModal label {
            font-weight: bold;
        }
        
        /* Add User button styling */
        .add-user-btn {
            margin-bottom: 20px;
        }
            
        /* View User button styling */
         #viewUserModal .modal-body p {
        margin-bottom: 8px;
    }
    
    #viewUserModal .modal-body strong {
        min-width: 120px;
        display: inline-block;
    }
    
    #viewUserModal h5 {
        color: #3a1450;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
    
    #viewUserModal hr {
        margin: 20px 0;
    }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("../includes/sidenav.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include("../includes/topnav.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h3 text-gray-800">User Management</h3>
                        <button type="button" class="btn btn-primary add-user-btn" data-toggle="modal" data-target="#addUserModal">
                            <i class="fas fa-plus-circle"></i> Add New User
                        </button>
                    </div>

                    <!-- Card for User Management -->
                    <div class="card">
                        <div class="card-body">
                            <!-- DataTable -->
                            <table id="usersTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Account Number</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Office Phone</th>
                                        <th>Fax</th>
                                        <th>User Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td style="text-align: center; width: 50px;">
                                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                                    <img src="<?= htmlspecialchars($user['image'] ?: '../images/default-avatar-icon-of-social-media-user-vector.jpg') ?>" 
                                                         alt="User Image" 
                                                         style="width: 50px; height: 50px; border-radius: 50%;">
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['account_number']) ?></td>
                                            <td>
                                                <?= htmlspecialchars(ucwords(strtolower($user['first_name'] . '  ' . $user['last_name']))) ?>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['office_phone']) ?></td>
                                            <td><?= htmlspecialchars($user['fax']) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-info view-button" data-userid="<?= $user['userid'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>

                                            <td>
                                                <button class="btn btn-primary edit-button" data-userid="<?= $user['userid'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                             
                                                <button type="button" class="btn btn-danger btn-delete-user" data-userid="<?= $user['userid'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include "../includes/footer.php"; ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript -->
 


    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addUserForm" method="POST" action="user_management.php">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <small id="emailHelp" class="form-text text-danger" style="display: none;">Email already exists!</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roles">Role *</label>
                                    <select class="form-control" id="roles" name="roles" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="practice_name">Practice Name</label>
                                    <input type="text" class="form-control" id="practice_name" name="practice_name">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="practice_address">Practice Address</label>
                                    <input type="text" class="form-control" id="practice_address" name="practice_address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="zip_code">Zip Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code">
                                </div>
                            </div>
                        </div>
                                             
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_address">Return Address</label>
                                    <input type="text" class="form-control" id="return_address" name="return_address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_city">Return City</label>
                                    <input type="text" class="form-control" id="return_city" name="return_city">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_state">Return State</label>
                                    <input type="text" class="form-control" id="return_state" name="return_state">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_zip_code">Return Zip Code</label>
                                    <input type="text" class="form-control" id="return_zip_code" name="return_zip_code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="office_phone">Office Phone</label>
                                    <input type="text" class="form-control" id="office_phone" name="office_phone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cell_phone">Cell Phone</label>
                                    <input type="text" class="form-control" id="cell_phone" name="cell_phone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fax">Fax</label>
                                    <input type="text" class="form-control" id="fax" name="fax">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="office_hours">Office Hours</label>
                                    <input type="text" class="form-control" id="office_hours" name="office_hours" placeholder="e.g., Mon-Fri 9am-5pm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="editUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_userid" name="userid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_first_name">First Name *</label>
                                    <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_last_name">Last Name *</label>
                                    <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_email">Email *</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_roles">Role *</label>
                                    <select class="form-control" id="edit_roles" name="roles" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_practice_name">Practice Name</label>
                                    <input type="text" class="form-control" id="edit_practice_name" name="practice_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_practice_address">Practice Address</label>
                                    <input type="text" class="form-control" id="edit_practice_address" name="practice_address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="edit_address">Address</label>
                                    <input type="text" class="form-control" id="edit_address" name="address">
                                </div>
                            </div>
                                  
                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="edit_office_hours">Office Hours</label>
                                <input type="text" class="form-control" id="edit_office_hours" name="office_hours" placeholder="e.g., Mon-Fri 9am-5pm">
                            </div>
                        </div>

                        </div>
                        
                        <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_city">City</label>
                                    <input type="text" class="form-control" id="edit_city" name="city">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_state">State</label>
                                    <input type="text" class="form-control" id="edit_state" name="state">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_zip_code">Zip Code</label>
                                    <input type="text" class="form-control" id="edit_zip_code" name="zip_code">
                                </div>
                            </div>

                              </div>
                                  <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_office_phone">Office Phone</label>
                                    <input type="text" class="form-control" id="edit_office_phone" name="office_phone">
                                </div>
                            </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_cell_phone">Cell Phone</label>
                                <input type="text" class="form-control" id="edit_cell_phone" name="cell_phone">
                            </div>
                        </div>
                        <div class="col-md-4">
                         <div class="form-group">
                            <label for="edit_fax">Fax</label>
                            <input type="text" class="form-control" id="edit_fax" name="fax">
                        </div>
                        </div>
                        </div>

<!-- In the editUserModal, after the fax field -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_return_address">Return Address</label>
            <input type="text" class="form-control" id="edit_return_address" name="return_address">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_return_city">Return City</label>
            <input type="text" class="form-control" id="edit_return_city" name="return_city">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_return_state">Return State</label>
            <input type="text" class="form-control" id="edit_return_state" name="return_state">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_return_zip_code">Return Zip Code</label>
            <input type="text" class="form-control" id="edit_return_zip_code" name="return_zip_code">
        </div>
    </div>
</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Initialize DataTable
    if (!$.fn.dataTable.isDataTable('#usersTable')) {
    $('#usersTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[1, 'desc']],
        "columnDefs": [
            { "className": "text-center", "targets": "_all" } // Center all columns
        ]
    });
}


        // Handle delete button click
        $('.btn-delete-user').on('click', function() {
            var userId = $(this).data('userid');

            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../functions/soft_delete_user.php',
                        type: 'POST',
                        data: { user_id: userId },
                        success: function(response) {
                            try {
                                var jsonResponse = JSON.parse(response);
                                if (jsonResponse.status === "success") {
                                    Swal.fire({
                                        toast: true,
                                        position: 'bottom-end',
                                        icon: 'success',
                                        title: 'The user has been deleted.',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        timerProgressBar: true,
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', jsonResponse.message, 'error');
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                                Swal.fire('Error!', 'Failed to delete the user.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to delete the user.', 'error');
                        }
                    });
                }
            });
        });

        // Handle Edit button click

$('.edit-button').on('click', function() {
    var userid = $(this).data('userid');

    $.ajax({
        url: '../functions/get_user.php',
        type: 'POST',
        data: { userid: userid },
        success: function(response) {
            try {
                var user = JSON.parse(response);

                // Populate all the modal fields
                $('#edit_userid').val(user.userid);
                $('#edit_first_name').val(user.first_name);
                $('#edit_last_name').val(user.last_name);
                $('#edit_email').val(user.email);
                $('#edit_office_phone').val(user.office_phone);
                $('#edit_cell_phone').val(user.cell_phone || '');
                $('#edit_office_hours').val(user.office_hours || '');
                $('#edit_fax').val(user.fax);
                $('#edit_roles').val(user.roles);
                $('#edit_practice_name').val(user.practice_name || '');
                $('#edit_practice_address').val(user.practice_address || '');
                $('#edit_address').val(user.address || '');
                $('#edit_city').val(user.city || '');
                $('#edit_state').val(user.state || '');
                $('#edit_zip_code').val(user.zip_code || '');
                $('#edit_return_address').val(user.return_address || '');
                $('#edit_return_city').val(user.return_city || '');
                $('#edit_return_state').val(user.return_state || '');
                $('#edit_return_zip_code').val(user.return_zip_code || '');

                $('#editUserModal').modal('show');
            } catch (e) {
                console.error('Error parsing user data:', e);
                Swal.fire('Error', 'Failed to fetch user data.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user:', error);
            Swal.fire('Error', 'Failed to fetch user data.', 'error');
        }
    });
});
// Handle form submit for editing user
$('#editUserForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: '../functions/update_user.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            try {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#editUserModal').modal('hide');
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        icon: 'success',
                        title: 'User details updated.',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed to update user.', 'error');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                Swal.fire('Error', 'Failed to update user.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating user:', error);
            Swal.fire('Error', 'Failed to update user.', 'error');
        }
    });
});

        // Check email availability when typing
        $('#email').on('blur', function() {
            var email = $(this).val();
            if (email) {
                $.ajax({
                    url: '../functions/check_email.php',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        if (response.exists) {
                            $('#emailHelp').text('Email already exists!').show();
                            $('#email').addClass('is-invalid');
                        } else {
                            $('#emailHelp').hide();
                            $('#email').removeClass('is-invalid');
                        }
                    },
                    dataType: 'json'
                });
            }
        });

// Handle add user form submission
$('#addUserForm').on('submit', function(e) {
    e.preventDefault();
    
    // Check if email is already in use
    if ($('#emailHelp').is(':visible')) {
        Swal.fire('Error', 'Email already exists. Please use a different email.', 'error');
        return false;
    }
    
    // Show loading indicator
    let timerInterval;
    Swal.fire({
        title: 'Creating Account',
        html: 'Please wait while we create the account and send the welcome email...',
        timerProgressBar: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Submit the form via AJAX
    $.ajax({
        url: 'user_management.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            Swal.close();
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    html: 'User account created successfully.<br><br>Welcome email has been sent to the user.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error!', response.message || 'Failed to add user.', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            // Handle cases where the response isn't JSON (like a redirect)
            if (xhr.responseText.includes('Location:')) {
                window.location.href = 'user_management.php?status=success';
            } else {
                try {
                    var response = JSON.parse(xhr.responseText);
                    Swal.fire('Error!', response.message || 'Failed to add user.', 'error');
                } catch (e) {
                    Swal.fire('Error!', 'Failed to add user: ' + error, 'error');
                }
            }
        }
    });
});

        // Show success/error messages from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Operation completed successfully',
                showConfirmButton: false,
                timer: 3000
            });
            // Clean the URL
            window.history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.get('status') === 'error') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: urlParams.get('message') || 'An error occurred',
                showConfirmButton: false,
                timer: 3000
            });
            // Clean the URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>
<!-- View User Modal -->

<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img id="view_image" src="../images/<?= htmlspecialchars($user['image'] ?: '../images/default-avatar-icon-of-social-media-user-vector.jpg') ?>" alt="User Image" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Account Number:</strong> <span id="view_account_number"></span></p>
                                <p><strong>Name:</strong> <span id="view_full_name"></span></p>
                                <p><strong>Email:</strong> <span id="view_email"></span></p>
                                <p><strong>Role:</strong> <span id="view_roles"></span></p>
                                <p><strong>Date Created:</strong> <span id="view_date_created"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Practice Name:</strong> <span id="view_practice_name"></span></p>
                                <p><strong>Practice Address:</strong> <span id="view_practice_address"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Contact Information</h5>
                        <p><strong>Office Phone:</strong> <span id="view_office_phone"></span></p>
                        <p><strong>Cell Phone:</strong> <span id="view_cell_phone"></span></p>
                        <p><strong>Fax:</strong> <span id="view_fax"></span></p>
                        <p><strong>Office Hours:</strong> <span id="view_office_hours"></span></p>
                        <p><strong>Office Days:</strong> 
                            <span id="view_office_days" style="display: inline-flex; flex-wrap: wrap; gap: 5px;"></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Address Information</h5>
                        <p><strong>Address:</strong> <span id="view_address"></span></p>
                        <p><strong>City:</strong> <span id="view_city"></span></p>
                        <p><strong>State:</strong> <span id="view_state"></span></p>
                        <p><strong>Zip Code:</strong> <span id="view_zip_code"></span></p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5>Return Address</h5>
                        <p><strong>Address:</strong> <span id="view_return_address"></span></p>
                        <p><strong>City:</strong> <span id="view_return_city"></span></p>
                        <p><strong>State:</strong> <span id="view_return_state"></span></p>
                        <p><strong>Zip Code:</strong> <span id="view_return_zip_code"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Handle View button click
// Handle View button click
$(document).on('click', '.view-button', function() {
    var userid = $(this).data('userid');
    
    $.ajax({
        url: '../functions/get_user.php',
        type: 'POST',
        data: { userid: userid },
        success: function(response) {
            try {
                var user = JSON.parse(response);
                
                // Populate the modal with user data
                $('#view_image').attr('src', '../images/' + (user.image || 'default-avatar-icon-of-social-media-user-vector.jpg'));
                $('#view_account_number').text(user.account_number);
                $('#view_full_name').text(user.first_name + ' ' + user.last_name);
                $('#view_email').text(user.email);
                $('#view_roles').text(user.roles.charAt(0).toUpperCase() + user.roles.slice(1));
                $('#view_date_created').text(new Date(user.date_created).toLocaleString());
                $('#view_practice_name').text(user.practice_name || 'N/A');
                $('#view_practice_address').text(user.practice_address || 'N/A');
                $('#view_office_phone').text(user.office_phone || 'N/A');
                $('#view_cell_phone').text(user.cell_phone || 'N/A');
                $('#view_fax').text(user.fax || 'N/A');
                $('#view_office_hours').text(user.office_hours || 'N/A');
                
                // Handle office days display
                const officeDaysContainer = $('#view_office_days');
                officeDaysContainer.empty();
                
                if (user.office_days) {
                    const days = user.office_days.split(',');
                    days.forEach(day => {
                        const trimmedDay = day.trim();
                        if (trimmedDay) {
                            officeDaysContainer.append(
                                `<span class="badge badge-primary" style="margin-right: 5px;">${trimmedDay}</span>`
                            );
                        }
                    });
                    
                    if (officeDaysContainer.children().length === 0) {
                        officeDaysContainer.parent().append('N/A');
                    }
                } else {
                    officeDaysContainer.parent().append('N/A');
                }
                
                $('#view_address').text(user.address || 'N/A');
                $('#view_city').text(user.city || 'N/A');
                $('#view_state').text(user.state || 'N/A');
                $('#view_zip_code').text(user.zip_code || 'N/A');
                $('#view_return_address').text(user.return_address || 'N/A');
                $('#view_return_city').text(user.return_city || 'N/A');
                $('#view_return_state').text(user.return_state || 'N/A');
                $('#view_return_zip_code').text(user.return_zip_code || 'N/A');
                
                $('#viewUserModal').modal('show');
            } catch (e) {
                console.error('Error parsing user data:', e);
                Swal.fire('Error', 'Failed to fetch user data.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user:', error);
            Swal.fire('Error', 'Failed to fetch user data.', 'error');
        }
    });
});
</script>
</body>
</html>