<?php  
//admin/changepassword.php
// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../home/index.php');
    exit();
}

// Include database connection
require "../includes/db.php";

// Get the logged-in user's email from session
$email = $_SESSION['email'];

// Prepare SQL statement to fetch user data
$sql = "SELECT userid, email, roles, first_name, last_name, account_number, image 
        FROM users 
        WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user data
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found with this email.";
    exit;
}

// Handle form submission
$imageError = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's an AJAX request
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Check current password
    $sqlCheckPassword = "SELECT password FROM users WHERE email = ?";
    $stmtCheckPassword = $conn->prepare($sqlCheckPassword);
    $stmtCheckPassword->bind_param("s", $email);
    $stmtCheckPassword->execute();
    $resultCheckPassword = $stmtCheckPassword->get_result();
    $userData = $resultCheckPassword->fetch_assoc();

    if ($userData && password_verify($currentPassword, $userData['password'])) {
        if ($newPassword === $confirmPassword && strlen($newPassword) > 0) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password in the database
            $sqlUpdate = "UPDATE users SET password = ? WHERE email = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ss", $hashedPassword, $email);
            if ($stmtUpdate->execute()) {
                // Respond with success
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'New password has been set.']);
                    exit;
                } else {
                    $successMessage = 'New password has been set.';
                }
            } else {
                $errorMessage = "Failed to update password.";
            }
        } else {
            $errorMessage = "The new passwords do not match.";
        }
    } else {
        $errorMessage = "Current password is incorrect.";
    }

    // If we reach here, something went wrong
    if ($isAjax) {
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }
}

// Only run this part if the user role is 'affiliate'
if ($user['roles'] === 'affiliate') {
    /**
     * Fetch user information based on the session email.
     *
     * @param mysqli $conn The database connection object.
     * @param string $email The user's email from the session.
     * @return array|null User information or null if not found.
     */
    function getUserInfoByEmail($conn, $email) {
        $sql = "SELECT * FROM users WHERE email = ? AND is_deleted = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Re-fetch user information using the function
    $user = getUserInfoByEmail($conn, $email);
}

// Default image if none exists
$defaultImage = "../images/default-avatar-icon-of-social-media-user-vector.jpg"; // Change to your default image path
$imageSrc = !empty($user['image']) ? "../images/" . htmlspecialchars($user['image']) : $defaultImage;
$fullName = $user ? htmlspecialchars(ucwords(strtolower($user['first_name'] . ' ' . $user['last_name']))) : "Guest";
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

   <style>
    :root {
      --primary: #0066cc;
      --primary-light: #e6f2ff;
      --secondary: #00a0e1;
      --dark: #1a2e4a;
      --light: #f8fafc;
      --gray: #64748b;
      --light-gray: #e2e8f0;
      --white: #ffffff;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
      --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
      --radius-sm: 0.25rem;
      --radius-md: 0.5rem;
      --radius-lg: 1rem;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.7;
    }

    /* Profile specific styles */
    .profile-image {
        width: 100%;
        max-width: 180px;
        height: auto;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .profile-card {
        max-width: 100%;
        box-shadow: var(--shadow-sm);
        border-radius: var(--radius-md);
    }
    
    .card {
        border: none;
        box-shadow: var(--shadow-sm);
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        border-radius: var(--radius-md) var(--radius-md) 0 0 !important;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th {
        background-color: var(--primary-light);
        padding: 0.75rem;
        text-align: left;
        font-size: 14px;
    }
    
    table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--light-gray);
    }
    
    table tr:last-child td {
        border-bottom: none;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        font-weight: 600;
    }

    /* Password specific styles */
    .input-group-text {
        cursor: pointer;
    }
    
    .notification {
        color: red;
        font-size: 0.9em;
        height: 20px;
        line-height: 20px;
        display: none;
    }
    
    @media (max-width: 768px) {
        .profile-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .table-responsive {
            overflow-x: auto;
            display: block;
            width: 100%;
        }
    }

    .sticky-footer a {
        text-decoration: none;
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
        
  <!-- Main Content -->
  <section class="about" id="profile" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Change Password</span>
                        <a href="userprofile.php" class="btn btn-light btn-sm">Cancel</a>
                    </div>
                    <div class="card-body">
                        <form id="passwordForm">
                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($successMessage)): ?>
                                <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                            <?php endif; ?>
                            <div class="profile-container">
                                <div class="me-lg-3 mb-3">
                                    <div class="card profile-card">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img src="<?php echo $imageSrc; ?>" 
                                                     alt="User Image" class="profile-image">
                                            </div>
                                            <span class="badge 
                                                <?php 
                                                    echo $user['roles'] === 'admin' ? 'bg-primary' : 
                                                        ($user['roles'] === 'superadmin' ? 'bg-danger' : 'bg-success');
                                                ?> text-uppercase">
                                                <?php 
                                                    echo $user['roles'] === 'user' ? 'User' : htmlspecialchars($user['roles']); 
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive flex-grow-1">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 30%;">Current Password:</th>
                                                <td style="width: 70%;">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="current_password" id="current_password" required>
                                                        <span class="input-group-text toggle-password" data-target="#current_password">
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>New Password:</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                                                        <span class="input-group-text toggle-password" data-target="#new_password">
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Confirm New Password:</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                                        <span class="input-group-text toggle-password" data-target="#confirm_password">
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                    </div>
                                                    <div id="passwordMatchNotification" class="notification"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-end">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
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
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    
  <script>
    $(document).ready(function() {
        // Toggle password visibility for all password fields
        $('.toggle-password').on('click', function() {
            const target = $(this).data('target');
            const input = $(target);
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Check for password match in real-time
        $('#new_password, #confirm_password').on('input', function() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            const notification = $('#passwordMatchNotification');

            if (newPassword.length > 0 && confirmPassword.length > 0) {
                if (newPassword !== confirmPassword) {
                    notification.text("The new passwords do not match.").show();
                } else {
                    notification.text("").hide();
                }
            } else {
                notification.text("").hide(); // Hide notification if fields are empty
            }
        });

        // Form submission
        $('#passwordForm').on('submit', function(e) {
            e.preventDefault();
            
            // Clear any previous messages
            $('.alert').remove();
            
            // Validate form
            const currentPassword = $('#current_password').val();
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                showAlert('error', 'Please fill in all fields');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showAlert('error', 'New passwords do not match');
                return;
            }
            
            // Submit via AJAX
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        // Optionally redirect after success
                        setTimeout(() => {
                            window.location.href = 'userprofile.php';
                        }, 2500);
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('error', 'An error occurred: ' + error);
                }
            });
        });
        
        function showAlert(type, message) {
            // Remove any existing alerts
            $('.alert').remove();
            
            // Create new alert
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `<div class="alert ${alertClass}">${message}</div>`;
            
            // Prepend to form
            $('#passwordForm').prepend(alertHtml);
            
            // Scroll to top to show message
            window.scrollTo(0, 0);
        }
    });
  </script>

</body>
</html>