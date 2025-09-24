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
// Function to get count of equipment for a tracking number
function getEquipmentCount($conn, $tracking_number) {
    $sql = "SELECT COUNT(*) as count FROM equipment_requests WHERE tracking_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tracking_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}
// Handle Update Inquiry Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_inquiry'])) {
    $id = $conn->real_escape_string($_POST['inquiry_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $response = $conn->real_escape_string($_POST['response'] ?? '');

    $sql = "UPDATE inquiries SET 
            status = '$status',
            response = '$response',
            responded_at = NOW(),
            responded_by = '$logged_in_user_id'
            WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Inquiry updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating inquiry: " . $conn->error;
    }
    header("Location: inquiries.php");
    exit();
}

// Fetch all non-deleted inquiries where account_number is NULL or empty
$sql = "SELECT * FROM inquiries WHERE is_deleted = 0 AND (account_number IS NULL OR account_number = '') ORDER BY submittedAt DESC";
$result = $conn->query($sql);

$inquiries = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $inquiries[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inquiries</title>
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
                    <div class="d-flex flex-wrap gap-2">
                            <a href="inquiries_user.php" class="btn btn-outline-info d-flex align-items-center justify-content-center" style="height: 50px; width: 120px; margin-right: 5px; margin-bottom: 5px;">
                                Registered
                            </a>

                            <a href="inquiries.php" class="btn btn-primary  d-flex align-items-center justify-content-center" style="height: 50px; width: 150px;">
                                Non-Registered
                            </a>
                    </div>

                    <!-- Inquiries Section -->
                    <div class="card shadow mb-4">
                        
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">

                            <h6 class="m-0 font-weight-bold text-primary">Client Inquiries</h6>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
  <table id="inquiryTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Tracking Number</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Request Type</th>
               <th>Equipment</th> <!-- New column -->
            <th>Address</th>
            <th>Message</th>
            <th>Date Submitted</th>
        </tr>
    </thead>
    <tbody>
       <?php foreach ($inquiries as $inquiry): ?>
    <tr>
        <td><?= htmlspecialchars($inquiry['tracking_number']) ?></td>
        <td><?= htmlspecialchars($inquiry['client_name']) ?></td>
        <td><?= htmlspecialchars($inquiry['email']) ?></td>
        <td><?= htmlspecialchars($inquiry['phone_number']) ?></td>
        <td><?= htmlspecialchars($inquiry['request_type']) ?></td>
        
        <!-- Equipment Button -->
        <td>
            <button 
                class="btn btn-secondary btn-sm view-equipment-btn"
                data-tracking="<?= htmlspecialchars($inquiry['tracking_number']) ?>"
            >
                View  (<?= getEquipmentCount($conn, $inquiry['tracking_number']) ?>)
            </button>
        </td>

        <!-- Address Button -->
        <td>
            <button 
                class="btn btn-info btn-sm view-address-btn"
                data-address="<?= htmlspecialchars($inquiry['address']) ?>"
                data-city="<?= htmlspecialchars($inquiry['city']) ?>"
                data-state="<?= htmlspecialchars($inquiry['state']) ?>"
                data-zip="<?= htmlspecialchars($inquiry['zip_code']) ?>"
            >
                View
            </button>
        </td>

        <!-- Message Button -->
        <td>
            <button 
                class="btn btn-primary btn-sm view-message-btn" 
                data-message="<?= htmlspecialchars($inquiry['message']) ?>"
            >
                View
            </button>
        </td>

        <td><?= date('M d, Y h:i A', strtotime($inquiry['submittedAt'])) ?></td>
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

    <!-- Update Inquiry Modal -->
    <div class="modal fade" id="updateInquiryModal" tabindex="-1" role="dialog" aria-labelledby="updateInquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateInquiryModalLabel">Update Inquiry Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="inquiries.php">
                    <input type="hidden" name="inquiry_id" id="inquiry_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="response">Response</label>
                            <textarea class="form-control" id="response" name="response" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="update_inquiry" class="btn btn-primary">Update Inquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle update button click
            $(document).on('click', '.update-btn', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var response = $(this).data('response');
                
                $('#inquiry_id').val(id);
                $('#status').val(status);
                $('#response').val(response);
                
                // Initialize the modal properly
                var updateModal = new bootstrap.Modal(document.getElementById('updateInquiryModal'));
                updateModal.show();
            });
            
            // Initialize DataTables
         $('#inquiryTable').DataTable({
    order: [[5, 'desc']], // your existing order setting
    columnDefs: [
        { targets: '_all', className: 'text-center' }
    ]
});

        });
    </script>
    
    <!-- Include Bootstrap JS bundle (should be after jQuery) -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Address modal
    document.querySelectorAll('.view-address-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const address = button.getAttribute('data-address');
            const city = button.getAttribute('data-city');
            const state = button.getAttribute('data-state');
            const zip = button.getAttribute('data-zip');

            Swal.fire({
                title: 'Delivery Address',
               html: `
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 12px; background-color: #f9f9f9; text-align: left;">
        <p><strong>Address:</strong> ${address}</p>
        <p><strong>City:</strong> ${city}</p>
        <p><strong>State:</strong> ${state}</p>
        <p><strong>ZIP:</strong> ${zip}</p>
    </div>
`,

                icon: 'info',
                confirmButtonText: 'Close'
            });
        });
    });

    // Message modal
    document.querySelectorAll('.view-message-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const message = button.getAttribute('data-message');
            Swal.fire({
                title: 'Client Message 💬',
                html: `<p>${message}</p>`,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        });
    });
});
</script>
<script>
    // Equipment modal
document.querySelectorAll('.view-equipment-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const trackingNumber = button.getAttribute('data-tracking');
        
        // Show loading state
        Swal.fire({
            title: 'Loading Equipment...',
            html: 'Please wait while we fetch the equipment details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch equipment data via AJAX
        fetch(`../functions/get_equipment.php?tracking_number=${trackingNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error
                    });
                    return;
                }
                
                // Format the equipment data into HTML
                let html = `<div class="equipment-list">`;
                
                if (data.length === 0) {
                    html += `<p>No equipment found for this request.</p>`;
                } else {
                    html += `
                    <style>
                        .equipment-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 15px;
                        }
                        .equipment-table th, .equipment-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        .equipment-table th {
                            background-color: #f2f2f2;
                        }
                        .pagination {
                            display: flex;
                            justify-content: center;
                            margin-top: 15px;
                        }
                        .pagination button {
                            margin: 0 5px;
                            padding: 5px 10px;
                            cursor: pointer;
                        }
                        .pagination button.active {
                            background-color: #007bff;
                            color: white;
                            border: none;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="equipment-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Serial Number</th>
                                    <th>Issue Description</th>
                                </tr>
                            </thead>
                            <tbody id="equipment-data">
                                <!-- Data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination" id="equipment-pagination">
                        <!-- Pagination will be inserted here -->
                    </div>`;
                }
                
                html += `</div>`;
                
                // Show the modal with the initial data
                Swal.fire({
                    title: `Equipment for Tracking #${trackingNumber}`,
                    html: html,
                    width: '800px',
                    showConfirmButton: false,
                    didOpen: () => {
                        // Initialize pagination
                        if (data.length > 0) {
                            setupEquipmentPagination(data, 5); // 5 items per page
                        }
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch equipment data'
                });
                console.error('Error:', error);
            });
    });
});

// Function to handle pagination
function setupEquipmentPagination(data, itemsPerPage) {
    let currentPage = 1;
    const totalPages = Math.ceil(data.length / itemsPerPage);
    
    // Function to display items for current page
    function displayItems() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, data.length);
        const pageData = data.slice(startIndex, endIndex);
        
        let html = '';
        pageData.forEach(item => {
            html += `
                <tr>
                    <td>${item.description || 'N/A'}</td>
                    <td>${item.serial_number || 'N/A'}</td>
                    <td>${item.issue_description || 'N/A'}</td>
                </tr>
            `;
        });
        
        document.getElementById('equipment-data').innerHTML = html;
        updatePaginationButtons();
    }
    
    // Function to update pagination buttons
    function updatePaginationButtons() {
        let paginationHtml = '';
        
        if (currentPage > 1) {
            paginationHtml += `<button onclick="changePage(${currentPage - 1})">&laquo; Previous</button>`;
        }
        
        // Show page numbers
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<button onclick="changePage(${i})" ${i === currentPage ? 'class="active"' : ''}>${i}</button>`;
        }
        
        if (currentPage < totalPages) {
            paginationHtml += `<button onclick="changePage(${currentPage + 1})">Next &raquo;</button>`;
        }
        
        document.getElementById('equipment-pagination').innerHTML = paginationHtml;
    }
    
    // Function to change page
    window.changePage = function(newPage) {
        currentPage = newPage;
        displayItems();
    };
    
    // Initial display
    displayItems();
}
</script>
</body>
</html>