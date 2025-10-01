<?php
// repair_tracking.php with save_repair functionality

// Headers for caching
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
header('Surrogate-Control: no-store');

// Start the session
session_start();

// Include database connection
require "../includes/db.php";

// Get the logged-in user's ID from session
$logged_in_user_id = $_SESSION['userid'] ?? null;

// Check if user ID is set in the session
if (!isset($logged_in_user_id)) {
    header('Location: ../login/index.php');
    exit();
}

// Handle save repair POST request
// Handle save repair POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    // Get POST data
    $client_id = $_POST['client_id'] ?? null;
    $tracking_number = $_POST['tracking_number'] ?? null;
    $equipment_type = $_POST['equipment_type'] ?? null;
    $brand = $_POST['brand'] ?? null;
    $model = $_POST['model'] ?? null;
    $serial_number = $_POST['serial_number'] ?? null;
    $issue_description = $_POST['issue_description'] ?? null;
    $technician_notes = $_POST['technician_notes'] ?? null;
    $repair_cost = $_POST['repair_cost'] ?? null;
    $shipping_tracking = $_POST['shipping_tracking'] ?? null;
    $received_date = $_POST['received_date'] ?? null;
    $estimated_completion = $_POST['estimated_completion'] ?? null;

    // Validate required fields
    if (!$client_id || !$tracking_number || !$equipment_type || !$brand || !$issue_description || !$received_date || !$estimated_completion) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    try {
        // First, check if tracking number already exists
        $tracking_check_query = "SELECT id FROM repairs WHERE tracking_number = ?";
        $tracking_check_stmt = $conn->prepare($tracking_check_query);
        $tracking_check_stmt->bind_param("s", $tracking_number);
        $tracking_check_stmt->execute();
        $tracking_check_result = $tracking_check_stmt->get_result();
        
        if ($tracking_check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Tracking number already exists']);
            exit();
        }

        // Get client details to store client_name - now from users table
        $client_query = "SELECT account_number, first_name, last_name FROM users WHERE userid = ?";
        $client_stmt = $conn->prepare($client_query);
        $client_stmt->bind_param("i", $client_id);
        $client_stmt->execute();
        $client_result = $client_stmt->get_result();
        $client = $client_result->fetch_assoc();
        
        if (!$client) {
            echo json_encode(['success' => false, 'message' => 'Client not found']);
            exit();
        }
        
        $client_name = $client['first_name'] . ' ' . $client['last_name'];
        $client_account_number = $client['account_number'];
        
        // Insert the new repair
        $query = "INSERT INTO repairs (
            client_id, 
            tracking_number, 
            account_number, 
            client_name, 
            equipment_type, 
            brand, 
            model, 
            serial_number, 
            issue_description, 
            technician_notes,
            repair_cost,
            shipping_tracking,
            received_date, 
            estimated_completion, 
            status
        ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "isssssssssdsss", 
            $client_id, 
            $tracking_number, 
            $client_account_number,
            $client_name, 
            $equipment_type, 
            $brand, 
            $model, 
            $serial_number, 
            $issue_description, 
            $technician_notes,
            $repair_cost,
            $shipping_tracking,
            $received_date, 
            $estimated_completion
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'tracking_number' => $tracking_number]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}

// Function to get repair tracking data
function getRepairTrackingData($conn) {
    $query = "SELECT * FROM repairs ORDER BY received_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get repair tracking data
$repairs = getRepairTrackingData($conn);

// Function to get all client data for mailing labels - now from users table
// Function to get all client data for mailing labels - now from users table
function getClientData($conn) {
    $query = "SELECT userid, account_number, first_name, last_name, practice_address, address, city, state, zip_code FROM users ORDER BY last_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get client data
$clients = getClientData($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Handpiece Repair Tracking</title>
    <?php include "../includes/icon.php"; ?>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Include SweetAlert2 (add to your <head> or before </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
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

        .status-received {
            color: #6c757d; 
            font-weight: bold;
        }

        .print-label-btn {
            margin-right: 5px;
        }
        
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
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
                    <!-- Repair Tracking Section -->
                           <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h3 text-gray-800">Handpiece Repair Tracking</h3>
                        <button type="button" class="btn btn-primary add-user-btn" data-toggle="modal" data-target="#addRepairModal">
                            <i class="fas fa-plus-circle"></i> Add New Repair
                        </button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="repairTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking #</th>
                                            <th>Account Number</th>
                                            <th>Client</th>
                                            <th>Equipment (Brand)</th>
                                            <th>Date</th>
                                            <th>Est. Completion</th>
                                            <th>Repair Cost</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($repairs as $repair): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($repair['tracking_number']) ?></td>
                                               <td><?= htmlspecialchars($repair['account_number']) ?></td>
                                            <td><?= htmlspecialchars($repair['client_name']) ?></td>
                                            <td><?= htmlspecialchars($repair['equipment_type']) ?> (<?= htmlspecialchars($repair['brand']) ?>)</td>
                                            <td><?= date('m/d/Y', strtotime($repair['received_date'])) ?></td>
                                            
                                            <td><?= date('m/d/Y', strtotime($repair['estimated_completion'])) ?></td>
                                            <td><?= htmlspecialchars($repair['repair_cost']) ?></td>
                                            <td>
                                                <?php 
                                                    $statusClass = '';
                                                    switch($repair['status']) {
                                                        case 'Pending': $statusClass = 'status-pending'; break;
                                                        case 'In Progress': $statusClass = 'status-in-progress'; break;
                                                        case 'Completed': $statusClass = 'status-completed'; break;
                                                        case 'Shipped': $statusClass = 'status-shipped'; break;
                                                        case 'Received': $statusClass = 'status-received'; break;
                                                    }
                                                ?>
                                                <span class="<?= $statusClass ?>"><?= $repair['status'] ?></span>
                                            </td>
                                            <td>
                                            <button class="btn btn-info btn-sm print-label-btn" 
                                                    data-client-id="<?= $repair['client_id'] ?>"
                                                    data-tracking="<?= $repair['tracking_number'] ?>">
                                                <i class="fas fa-tag"></i> Print Label
                                            </button>
                                                <button class="btn btn-warning btn-sm edit-repair" 
                                                        data-id="<?= $repair['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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

    <!-- Single Label Print Preview -->
    <div id="singleLabelPreview" style="display:none;"></div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <!-- Add Repair Modal -->
<div class="modal fade" id="addRepairModal" tabindex="-1" role="dialog" aria-labelledby="addRepairModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRepairModalLabel">Add New Repair</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addRepairForm">
                    <div class="form-group">
                        <label for="trackingNumber">Tracking Number *</label>
                        <input type="text" class="form-control" id="trackingNumber" required 
                               placeholder="e.g., DER-12345" pattern="[A-Za-z]{2,4}-\d{3,8}"
                               title="Format: 2-4 letters, hyphen, 3-8 digits (e.g., DER-12345)">
                        <small class="form-text text-muted">Format: ABC-12345 (2-4 letters, hyphen, 3-8 digits)</small>
                    </div>
                    <div class="form-group">
                        <label for="clientSelect">Client *</label>
                        <select class="form-control" id="clientSelect" required>
                            <option value="">Select Client</option>
                            <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['userid'] ?>">
                                <?= htmlspecialchars($client['first_name']) ?> <?= htmlspecialchars($client['last_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="equipmentType">Equipment Type *</label>
                        <input type="text" class="form-control" id="equipmentType" required placeholder="e.g., High Speed Handpiece">
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand *</label>
                        <input type="text" class="form-control" id="brand" required placeholder="e.g., KaVo, Bien Air">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" placeholder="e.g., 650B">
                    </div>
                    <div class="form-group">
                        <label for="serialNumber">Serial Number</label>
                        <input type="text" class="form-control" id="serialNumber" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="issueDescription">Issue Description *</label>
                        <textarea class="form-control" id="issueDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="technicianNotes">Technician Notes</label>
                        <textarea class="form-control" id="technicianNotes" rows="3" placeholder="Optional notes from technician"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="repairCost">Repair Cost ($)</label>
                        <input type="number" step="0.01" class="form-control" id="repairCost" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label for="shippingTracking">Shipping Tracking #</label>
                        <input type="text" class="form-control" id="shippingTracking" placeholder="Optional return tracking number">
                    </div>
                    <div class="form-group">
                        <label for="receivedDate">Received Date *</label>
                        <input type="date" class="form-control" id="receivedDate" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="estimatedCompletion">Estimated Completion *</label>
                        <input type="date" class="form-control" id="estimatedCompletion" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRepair">Save Repair</button>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Repair Modal -->
    <div class="modal fade" id="editRepairModal" tabindex="-1" role="dialog" aria-labelledby="editRepairModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editRepairForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRepairModalLabel">Edit Repair</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="repair_id" id="repair_id">

                        <div class="form-group">
                        <label for="edit_equipment_type">Equipment Type</label>
                        <input type="text" class="form-control" name="equipment_type" id="edit_equipment_type" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_brand">Brand</label>
                        <input type="text" class="form-control" name="brand" id="edit_brand" required>
                    </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control text-secondary rounded" name="status" id="status" style="height: 35px;">
                                <option>Pending</option>
                                <option>In Progress</option>
                                <option>Shipped</option>
                                <option>Completed</option>
                               
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="estimated_completion">Estimated Completion</label>
                            <input type="date" class="form-control" name="estimated_completion" id="estimated_completion">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#repairTable').DataTable({
            "pageLength": 10,
            "order": [[4, "desc"]], // Default sort by Received Date
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Disable ordering on Actions column
            ]
        });
        
 // Print Shipping Label for Repair
$('.print-label-btn').click(function() {
    const clientId = $(this).data('client-id');
    const trackingNumber = $(this).data('tracking');
    
    // Get client data from the clients array
    const client = <?= json_encode($clients) ?>.find(c => c.userid == clientId);
    
    if (client) {
        // Use practice_address if available, otherwise use address
        const address = client.practice_address || client.address || '';
        
        const labelHtml = `
            <div class="mailing-label" style="
                width: 400px;
                padding: 20px;
                border: 2px dashed #333;
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.5;
                color: #000;
                background-color: #fff;
            ">
                <div class="client-name" style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">
                    ${client.first_name} ${client.last_name}
                </div>
                <div class="client-address" style="margin-bottom: 15px;">
                    ${address}<br>
                    ${client.city}, ${client.state} ${client.zip_code}
                </div>
                <div class="tracking-info" style="margin-bottom: 15px;">
                    <strong>Tracking #:</strong> ${trackingNumber}<br>
                    <strong>Contents:</strong> Repaired Dental Handpiece
                </div>
                <div class="return-address" style="font-size: 11px; border-top: 1px solid #ccc; padding-top: 10px;">
                    <strong>RETURN SHIPPING LABEL</strong><br>
                    Furnished Unfurnished<br>
                    1967 Lockbourne Road Suite B<br>
                    Columbus, Ohio 43207
                </div>
            </div>
        `;
        
        $('#singleLabelPreview').html(labelHtml);
        const printWindow = window.open('', '', 'width=600,height=600');
        printWindow.document.write('<html><head><title>Shipping Label</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { margin: 0; padding: 10px; }
            .mailing-label {
                border: 1px dashed #ccc;
                padding: 15px;
                height: 3.5in;
                width: 4in;
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
            .client-name {
                font-weight: bold;
                font-size: 14px;
                margin-bottom: 5px;
            }
            .client-address {
                line-height: 1.4;
            }
        `);
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(labelHtml);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    } else {
        Swal.fire('Error', 'Client information not found', 'error');
    }
});   

    // Handle save repair button click
$('#saveRepair').click(function() {
    // Get form data
    const repairData = {
        client_id: $('#clientSelect').val(),
        tracking_number: $('#trackingNumber').val(),
        equipment_type: $('#equipmentType').val(),
        brand: $('#brand').val(),
        model: $('#model').val(),
        serial_number: $('#serialNumber').val(),
        issue_description: $('#issueDescription').val(),
        technician_notes: $('#technicianNotes').val(),
        repair_cost: $('#repairCost').val(),
        shipping_tracking: $('#shippingTracking').val(),
        received_date: $('#receivedDate').val(),
        estimated_completion: $('#estimatedCompletion').val()
    };

    // Validate required fields
    if (!repairData.client_id || !repairData.tracking_number || !repairData.equipment_type || 
        !repairData.brand || !repairData.issue_description || !repairData.received_date || 
        !repairData.estimated_completion) {
        Swal.fire('Error', 'Please fill in all required fields', 'error');
        return;
    }

    // Send AJAX request
    $.ajax({
        url: 'repair_tracking.php',
        method: 'POST',
        data: repairData,
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Repair added successfully. Tracking #: ' + data.tracking_number,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#addRepairModal').modal('hide');
                        location.reload(); // Refresh to show the new repair
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to add repair', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Invalid server response', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to connect to server', 'error');
        }
    });
});
        // Reset form when modal is closed
        $('#addRepairModal').on('hidden.bs.modal', function() {
            $('#addRepairForm')[0].reset();
        });

// Edit repair functionality
$('.edit-repair').click(function() {
    const repairId = $(this).data('id');

    $.get('../functions/get_repair.php?id=' + repairId, function(response) {
        try {
            const data = JSON.parse(response);
            if (data.success) {
                const r = data.repair;
                $('#repair_id').val(r.id);
                $('#edit_equipment_type').val(r.equipment_type);
                $('#edit_brand').val(r.brand);
                $('#status').val(r.status);
                $('#estimated_completion').val(r.estimated_completion);

                $('#editRepairModal').modal('show');
            } else {
                Swal.fire('Error', 'Failed to load repair details', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Invalid server response', 'error');
        }
    });
});

    $('#editRepairForm').submit(function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Confirm Changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = $(this).serialize();

            $.post('../functions/update_repair.php', formData, function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Close the modal
                        $('#editRepairModal').modal('hide');

                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Repair updated successfully!',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 2500);
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'error',
                            title: 'Failed to update repair',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }
                } catch (e) {
                    Swal.fire('Error', 'Invalid server response', 'error');
                }
            });
        }
    });
});

   
    });


    </script>
    
</body>
</html>