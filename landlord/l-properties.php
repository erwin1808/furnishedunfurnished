<?php
// VERY FIRST LINE - NO SPACES, NO LINE BREAKS BEFORE THIS
require_once '../includes/init.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard | Furnished/Unfurnished</title>

    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="../assets/css/styles.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
</head>

<body id="page-top">

<div id="wrapper">
    <?php include '../includes/l-sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
    <?php include '../includes/l-topbar.php'; ?>
            <!-- End Topbar -->

            <div class="container-fluid">
                <!-- Header -->
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-4 p-3 bg-white shadow-sm rounded-3 border-start border-4 border-success">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">Property Listings Overview</h1>
                        <p class="text-muted mb-0 small">Manage, review, and update all listed properties efficiently.</p>
                    </div>
                    <a href="add_property.php" class="btn btn-primary btn-sm px-4 py-2 mt-3 mt-sm-0 shadow-sm rounded-pill">
                        <i class="fas fa-plus me-2 text-white-50"></i> Add New Property
                    </a>
                </div>

                <!-- Tabs -->
                <div class="row mb-4">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="propertyTabs" role="tablist">
                 <li class="nav-item">
                        <?php
                    // Start session if not already started
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    // Include database connection
                    include '../includes/db.php';

                    // Get logged-in user's account number safely
                    $account_number = $_SESSION['account_number'] ?? null;

                    // Initialize active count
                    $activeCount = 0;

                    // Only query if account number is available
                    if ($account_number) {
                        $countQuery = "SELECT COUNT(intake_id) AS active_count 
                                    FROM property 
                                    WHERE is_approve = 1 AND account_number = ?";
                        
                        $stmt = $conn->prepare($countQuery);
                        $stmt->bind_param("s", $account_number);
                        $stmt->execute();
                        $countResult = $stmt->get_result();
                        
                        if ($countResult) {
                            $row = $countResult->fetch_assoc();
                            $activeCount = (int)$row['active_count'];
                        }
                        
                        $stmt->close();
                    }
                    ?>

                    <!-- 👇 Add 'active' here -->
                    <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-selected="true">
                        <span style="color: #00524e; font-weight: 500;">Active</span>
                        <span class="badge bg-danger ms-2" id="activeCount"><?= $activeCount ?></span>
                    </a>
                </li>

                            <li class="nav-item">
                          <?php
                            // Start session if not already started
                            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                            }

                            // Include database connection
                            include '../includes/db.php';

                            // Get logged-in user's account number safely
                            $account_number = $_SESSION['account_number'] ?? null;

                            // Initialize pending count
                            $pendingCount = 0;

                            // Only query if account number is available
                            if ($account_number) {
                                $countQuery = "SELECT COUNT(intake_id) AS pending_count 
                                            FROM property 
                                            WHERE is_approve = 0 AND account_number = ?";
                                
                                $stmt = $conn->prepare($countQuery);
                                $stmt->bind_param("s", $account_number);
                                $stmt->execute();
                                $countResult = $stmt->get_result();
                                
                                if ($countResult) {
                                    $row = $countResult->fetch_assoc();
                                    $pendingCount = (int)$row['pending_count'];
                                }
                                
                                $stmt->close();
                            }
                            ?>

                                <a class="nav-link" id="Pending-tab" data-toggle="tab" href="#Pending" role="tab">
                                    <span style="color:  #00524e; font-weight: 500;">Pending</span>
                                    <span class="badge bg-danger ms-2" id="pendingCount"><?= $pendingCount ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
                                   <span style="color:  #00524e; font-weight: 500;">Rejected</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0 rounded-bottom shadow-sm" id="propertyTabsContent">
                            <!-- Active Properties -->
                            <div class="tab-pane fade show active" id="active" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                   <table id="activeTable" class="table table-bordered table-striped table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Property Code</th>
                                                <th>Property Title</th>
                                                <th>Type</th>
                                                <th>Address</th>
                                                <th>City, State</th>
                                                <th>Submitted</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                          <tbody>
<?php
$account_number = $_SESSION['account_number']; 

$query = "SELECT intake_id, property_code, property_title, property_type, street, city, province, date_created
          FROM property 
          WHERE is_approve = 1 AND account_number = ?
          ORDER BY date_created DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $account_number);
$stmt->execute();
$result = $stmt->get_result();
$counter = 1;

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$counter}</td>";
    echo "<td>" . htmlspecialchars($row['property_code']) . "</td>";
    echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
    echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['street']) . "</td>";
    echo "<td>" . htmlspecialchars($row['city'] . ', ' . $row['province']) . "</td>";
    echo "<td>" . date('M d, Y h:i A', strtotime($row['date_created'])) . "</td>";
    echo "<td>
            <a href='view_property.php?id={$row['intake_id']}' class='btn btn-sm btn-info'><i class='fas fa-eye'></i> View</a>
            <a href='edit_property.php?id={$row['intake_id']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i> Edit</a>
            <button class='btn btn-sm btn-danger delete-btn' data-id='{$row['intake_id']}'><i class='fas fa-trash'></i> Delete</button>
        </td>";
    echo "</tr>";
    $counter++;
}
$stmt->close();
?>
</tbody>

                                    </table>
                                </div>
                            </div>

                      <!-- Pending Properties -->
<div class="tab-pane fade" id="Pending" role="tabpanel">
    <div class="table-responsive mt-3">
        <table id="pendingTable" class="table table-bordered table-striped table-hover">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Property Code</th>
                    <th>Property Title</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>City, State</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $account_number = $_SESSION['account_number'];
                
                $query = "SELECT intake_id, property_code, property_title, property_type, street, city, province, date_created
                          FROM property 
                          WHERE is_approve = 0 AND account_number = ?
                          ORDER BY date_created DESC";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $account_number);
                $stmt->execute();
                $result = $stmt->get_result();
                $counter = 1;
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$counter}</td>";
                        echo "<td>" . htmlspecialchars($row['property_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['street']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['city'] . ', ' . $row['province']) . "</td>";
                        echo "<td>" . date('M d, Y h:i A', strtotime($row['date_created'])) . "</td>";
                        echo "<td>
                                <a href='view_property.php?id={$row['intake_id']}' class='btn btn-sm btn-info'><i class='fas fa-eye'></i> View</a>
                                <a href='edit_property.php?id={$row['intake_id']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i> Edit</a>
                                <button class='btn btn-sm btn-danger delete-btn' data-id='{$row['intake_id']}'><i class='fas fa-trash'></i> Delete</button>
                            </td>";
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    // FIX: Create a proper row with all 8 columns instead of using colspan
                    echo "<tr>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>No pending properties</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                            <td colspan='1' class='text-center text-muted'>-</td>
                          </tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

                            <!-- Rejected Properties -->
                            <div class="tab-pane fade" id="rejected" role="tabpanel">
                                <p class="text-muted">List of rejected properties will appear here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /container-fluid -->
        </div> <!-- /content -->
    </div> <!-- /content-wrapper -->
</div> <!-- /wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // Initialize Active Table if it exists
    if ($('#activeTable').length) {
        $('#activeTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 20, 50],
            "order": [[0, "asc"]],
            "columnDefs": [{ "orderable": false, "targets": 7 }],
            "language": { 
                "search": "Search Property:",
                "emptyTable": "No active properties found."
            }
        });
    }

    // Initialize Pending Table if it exists
    if ($('#pendingTable').length) {
        $('#pendingTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 20, 50],
            "order": [[0, "asc"]],
            "columnDefs": [{ "orderable": false, "targets": 7 }], // Fixed: Changed target to 7
            "language": { 
                "search": "Search Property:",
                "emptyTable": "No pending properties found."
            }
        });
    }

    // Redraw when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().draw();
    });

    // SweetAlert delete confirmation
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This property will be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_property.php',
                    method: 'POST',
                    data: { id },
                    success: function () {
                        Swal.fire('Deleted!', 'Property has been removed.', 'success')
                            .then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire('Error', 'Unable to delete property.', 'error');
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>
