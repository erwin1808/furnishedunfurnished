<?php
// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
header('Surrogate-Control: no-store');

// Start the session
session_start();

// Include database connection
require "../includes/db.php";

// Get the logged-in user's ID and role from session
$logged_in_user_id = $_SESSION['userid'] ?? null;

// Check if user ID is set in the session
if (!$logged_in_user_id) {
    header('Location: ../login/index.php');
    exit();
}

// Handle Add Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $service_make = $conn->real_escape_string($_POST['service_make']);
    $basic_service = $conn->real_escape_string($_POST['basic_service']);
    $complete_overhaul = $conn->real_escape_string($_POST['complete_overhaul']);
    $warranty = $conn->real_escape_string($_POST['warranty']);
    $type = $conn->real_escape_string($_POST['type']);

    $sql = "INSERT INTO service (service_make_manufacturer, basic_service, complete_overhaul, warranty, type) 
            VALUES ('$service_make', '$basic_service', '$complete_overhaul', '$warranty', '$type')";
    
    if ($conn->query($sql)) {  // <-- fixed missing closing parenthesis
        $_SESSION['success_message'] = "Service added successfully!";
    } else {
        $_SESSION['error_message'] = "Error adding service: " . $conn->error;
    }
    header("Location: services_offered.php");
    exit();
}

// Handle Edit Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $id = $conn->real_escape_string($_POST['edit_id']);
    $service_make = $conn->real_escape_string($_POST['edit_service_make']);
    $basic_service = $conn->real_escape_string($_POST['edit_basic_service']);
    $complete_overhaul = $conn->real_escape_string($_POST['edit_complete_overhaul']);
    $warranty = $conn->real_escape_string($_POST['edit_warranty']);
    $type = $conn->real_escape_string($_POST['edit_type']);

    $sql = "UPDATE service SET 
            service_make_manufacturer = '$service_make',
            basic_service = '$basic_service',
            complete_overhaul = '$complete_overhaul',
            warranty = '$warranty',
            type = '$type'
            WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Service updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating service: " . $conn->error;
    }
    header("Location: services_offered.php");
    exit();
}

// Handle Soft Delete Service
if (isset($_GET['delete_id'])) {
    $id = $conn->real_escape_string($_GET['delete_id']);
    
    $sql = "UPDATE service SET is_deleted = 1 WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Service deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting service: " . $conn->error;
    }
    header("Location: services_offered.php");
    exit();
}

// Fetch all non-deleted services
$sql = "SELECT * FROM service WHERE is_deleted = 0 ORDER BY service_make_manufacturer ASC";
$result = $conn->query($sql);
$services = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Services Offered</title>
    <?php include "../includes/icon.php"; ?>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .repair-table {
            width: 100%;
            margin-top: 20px;
        }
        
        .repair-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 8px;
        }
        
        .repair-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-in-progress {
            color: #17a2b8;
            font-weight: bold;
        }
        
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-shipped {
            color: #007bff;
            font-weight: bold;
        }
        
        .print-label-btn {
            margin-right: 5px;
        }
        
        .action-btns .btn {
            margin-right: 5px;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include ("../includes/sidenav.php");?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include ("../includes/topnav.php");?>
                <div class="container-fluid">
                    <!-- Display success/error messages -->
           <?php if (isset($_SESSION['success_message'])): ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: '<?php echo $_SESSION['success_message']; ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'error',
            title: '<?php echo $_SESSION['error_message']; ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

                                  <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h3 text-gray-800">Services Offered</h3>
                        <button type="button" class="btn btn-primary add-user-btn" data-toggle="modal" data-target="#addServiceModal">
                            <i class="fas fa-plus-circle"></i> Add New Repair
                        </button>
                    </div>
                    <!-- Services Section -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="serviceTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service/Make/Manufacturer</th>
                                            <th>Basic Service ($)</th>
                                            <th>Complete Overhaul ($)</th>
                                            <th>Warranty</th>
                                            <th>Service Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['service_make_manufacturer']); ?></td>
                                            <td><?php echo htmlspecialchars($service['basic_service']); ?></td>
                                            <td><?php echo htmlspecialchars($service['complete_overhaul']); ?></td>
                                            <td><?php echo htmlspecialchars($service['warranty']); ?></td>
                                            <td><?php echo htmlspecialchars($service['type']); ?></td>
                                            <td class="action-btns">
                                                <button class="btn btn-sm btn-primary edit-btn" 
                                                    data-id="<?php echo $service['id']; ?>"
                                                    data-service_make="<?php echo htmlspecialchars($service['service_make_manufacturer']); ?>"
                                                    data-basic_service="<?php echo htmlspecialchars($service['basic_service']); ?>"
                                                    data-complete_overhaul="<?php echo htmlspecialchars($service['complete_overhaul']); ?>"
                                                    data-warranty="<?php echo htmlspecialchars($service['warranty']); ?>"
                                                    data-type="<?php echo htmlspecialchars($service['type']); ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="services_offered.php?delete_id=<?php echo $service['id']; ?>" 
                                                   class="btn btn-sm btn-danger delete-btn">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <?php include "../includes/footer.php";?>
            </footer>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="services_offered.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="service_make">Service/Make/Manufacturer</label>
                            <input type="text" class="form-control" id="service_make" name="service_make" required>
                        </div>
                        <div class="form-group">
                            <label for="basic_service">Basic Service ($)</label>
                            <input type="number" step="0.01" class="form-control" id="basic_service" name="basic_service" required>
                        </div>
                        <div class="form-group">
                            <label for="complete_overhaul">Complete Overhaul ($)</label>
                            <input type="number" step="0.01" class="form-control" id="complete_overhaul" name="complete_overhaul" required>
                        </div>
                        <div class="form-group">
                            <label for="warranty">Warranty (specify if "months" or "years")</label>
                            <input type="text" class="form-control" id="warranty" name="warranty" required>
                        </div>
                    <div class="form-group">
                    <label for="type">Service Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="" disabled selected>Select a service type</option>
                        <option value="Highspeed Handpiece">Highspeed Handpiece</option>
                        <option value="Lowspeed Handpiece">Lowspeed Handpiece</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_service" class="btn btn-primary">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="services_offered.php">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_service_make">Service/Make/Manufacturer</label>
                            <input type="text" class="form-control" id="edit_service_make" name="edit_service_make" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_basic_service">Basic Service ($)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_basic_service" name="edit_basic_service" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_complete_overhaul">Complete Overhaul ($)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_complete_overhaul" name="edit_complete_overhaul" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_warranty">Warranty</label>
                            <input type="text" class="form-control" id="edit_warranty" name="edit_warranty" required>
                        </div>
                  <div class="form-group">
    <label for="edit_type">Service Type</label>
    <select class="form-control" id="edit_type" name="edit_type" required>
        <option value="" disabled selected>Select a service type</option>
        <option value="Highspeed Handpiece">Highspeed Handpiece</option>
        <option value="Lowspeed Handpiece">Lowspeed Handpiece</option>
        <option value="Other">Other</option>
    </select>
</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_service" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Previous PHP code remains the same until the JavaScript section -->
    <script>
        $(document).ready(function() {
            // Handle edit button click - using event delegation
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var service_make = $(this).data('service_make');
                var basic_service = $(this).data('basic_service');
                var complete_overhaul = $(this).data('complete_overhaul');
                var warranty = $(this).data('warranty');
                var type = $(this).data('type');
                
                $('#edit_id').val(id);
                $('#edit_service_make').val(service_make);
                $('#edit_basic_service').val(basic_service);
                $('#edit_complete_overhaul').val(complete_overhaul);
                $('#edit_warranty').val(warranty);
                $('#edit_type').val(type);
                
                // Initialize the modal properly
                var editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
                editModal.show();
            });
            
            // Handle delete button click with confirmation
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('href');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
    
    <!-- Include Bootstrap JS bundle (should be after jQuery) -->

    
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
    $(document).ready(function() {
        $('#serviceTable').DataTable({
            // Optional: Customize language, pagination, etc. here
            // Example: 
            // "pagingType": "simple_numbers",
            // "lengthMenu": [5, 10, 25, 50],
            // "order": [[ 0, "asc" ]]
        });
    });
    </script>
</body>
</html>