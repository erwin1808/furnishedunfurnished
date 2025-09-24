<?php

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


// Fetch users for DataTable
$sql = "
SELECT 
 u.userid, 
 u.date_created, 
 u.account_number, 
 u.email, 
 u.roles, 
 u.office_phone, 
 u.fax, 
 u.first_name, 
 u.last_name, 
 u.image,
 u.practice_name,
 u.practice_address,
 u.address,
 u.city,
 u.state,
 u.zip_code
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
            'fax' => $row['fax'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'image' => $row['image'] ? '../images/' . $row['image'] : '../images/undraw_profile.svg',
            'practice_name' => $row['practice_name'],
            'practice_address' => $row['practice_address'],
            'address' => $row['address'],
            'city' => $row['city'],
            'state' => $row['state'],
            'zip_code' => $row['zip_code']
        ];
    }
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
                <div class="container-fluid" style="margin-top: -80px;">
     

 
  <!-- Profile Section -->
  <section class="about" id="profile" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>User Details</span>
                        <!--a href="edit_profile.php" class="btn btn-primary btn-sm">Edit Profile</a-->
                    </div>
                    <div class="card-body">
                        <div class="profile-container">
                            <div class="me-lg-3 mb-3">
                                <div class="card profile-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img src="../images/<?php echo !empty($user['image']) ? htmlspecialchars($user['image']) : 'default-avatar-icon-of-social-media-user-vector.jpg'; ?>" 
                                                 alt="User Image" class="profile-image">
                                        </div>
                                        <span class="badge 
                                            <?php 
                                                echo $user['roles'] === 'admin' ? 'bg-primary' : 
                                                    ($user['roles'] === 'superadmin' ? 'bg-danger' : 'bg-success');
                                            ?> text-uppercase">
                                            <?php 
                                                echo $user['roles'] === 'user' ? 'Customer' : htmlspecialchars($user['roles']); 
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive flex-grow-1">
                         <table class="table">
    <tbody>
        <tr>
            <th style="width: 15%;">First Name:</th>
            <td style="width: 35%;"><?php echo htmlspecialchars($user['first_name']); ?></td>
            <th style="width: 15%;">Last Name:</th>
            <td style="width: 35%;"><?php echo htmlspecialchars($user['last_name']); ?></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <th>Acccount No:</th>
            <td><?php echo htmlspecialchars($user['account_number']); ?></td>
        </tr>
        <tr>
            <th>Practice Name:</th>
            <td><?php echo htmlspecialchars($user['practice_name']); ?></td>
            <th>Practice Address:</th>
            <td><?php echo htmlspecialchars($user['practice_address']); ?></td>
        </tr>
        <tr>
            <th>Address:</th>
            <td><?php echo htmlspecialchars($user['address']); ?></td>
            <th>City:</th>
            <td><?php echo htmlspecialchars($user['city']); ?></td>
        </tr>
        <tr>
            <th>State:</th>
            <td><?php echo htmlspecialchars($user['state']); ?></td>
            <th>Zip Code:</th>
            <td><?php echo htmlspecialchars($user['zip_code']); ?></td>
        </tr>
    </tbody>
                            </table>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacts Section -->
                <div class="card mb-4">
                    <div class="card-header">Contact Information</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" >
                                <thead>
                                    <tr>
                                        <th style="width: 50%; ">Platform</th>
                                        <th>Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <i class="fab fa-whatsapp text-success" style="font-size: 20px; margin-right: 5px;"></i>
                                            Phone Number
                                        </td>
                                        <td><?php echo htmlspecialchars($user['office_phone']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fas fa-fax text-primary" style="font-size: 20px; margin-right: 5px;"></i>
                                            Fax
                                        </td>
                                        <td><?php echo htmlspecialchars($user['fax']); ?></td>
                                    </tr>
                                    <?php if ($user['office_phone'] === "N/A" && $user['fax'] === "N/A"): ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No contact information available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
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
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>

</body>
</html>