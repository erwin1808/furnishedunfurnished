<?php include '../includes/session_check.php'; ?>
<?php
// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_email'])) { // <-- match your login session
    header("Location: ../index.php");
    exit;
}

// Redirect based on user_type stored in session
if (isset($_SESSION['user_type'])) {
    switch ($_SESSION['user_type']) {
        case 'admin':
            break; // Admin stays
        case 'tenant':
            header("Location: ../tenant/dashboard.php");
            exit;
        case 'landlord':
            header("Location: ../landlord/dashboard.php");
            exit;
        default:
            header("Location: ../index.php");
            exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
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
    <?php include '../includes/sidenav.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
    <?php include '../includes/topnav.php'; ?>
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
                            include '../includes/db.php';
                            $activeCount = 0;
                            $countQuery = "SELECT COUNT(intake_id) AS active_count FROM property WHERE is_approve = 1";
                            $countResult = mysqli_query($conn, $countQuery);
                            if ($countResult) {
                                $row = mysqli_fetch_assoc($countResult);
                                $activeCount = (int)$row['active_count'];
                            }
                            ?>
                            <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-selected="true">
                                <span style="color: #00524e; font-weight: 500;">All Properties</span>
                            </a>
                        </li>

                            <li class="nav-item">
                                <?php
                                include '../includes/db.php';
                                $IncompleteCount = 0;
                                $countQuery = "SELECT COUNT(intake_id) AS Incomplete_count FROM property WHERE is_approve = 0";
                                $countResult = mysqli_query($conn, $countQuery);
                                if ($countResult) {
                                    $row = mysqli_fetch_assoc($countResult);
                                    $IncompleteCount = (int)$row['Incomplete_count'];
                                }
                                ?>
                                <a class="nav-link" id="Incomplete-tab" data-toggle="tab" href="#Incomplete" role="tab">
                                    <span style="color:  #00524e; font-weight: 500;">Incomplete</span>
                                    <span class="badge bg-danger ms-2" id="IncompleteCount"><?= $IncompleteCount ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Listed-tab" data-toggle="tab" href="#Listed" role="tab">
                                   <span style="color:  #00524e; font-weight: 500;">Listed</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0 rounded-bottom shadow-sm" id="propertyTabsContent">
                            <!-- Active Properties -->
                            <div class="tab-pane fade show active" id="active" role="tabpanel">
                                   <div class="table-responsive mt-3">
                                <table id="activePropertiesTable" class="table table-bordered table-striped table-hover">
                                    <thead class="table-success">
                                        <tr>
                                            <th>#</th>
                                            <th>Property Code</th>
                                            <th>Property Title</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>City, State</th>
                                            <th>Landlord</th>
                                            <th>Incomplete</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT p.intake_id, p.property_code, p.property_title, p.property_type, p.street, p.city, p.province, p.date_created, p.account_number
                                                FROM property p
                                                WHERE p.is_approve = 0
                                                ORDER BY p.date_created DESC";
                                        $result = mysqli_query($conn, $query);
                                        $counter = 1;
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Fetch landlord name
                                                $landlord_name = '';
                                                if (!empty($row['account_number'])) {
                                                    $userQuery = "SELECT first_name, last_name FROM users WHERE account_number = '{$row['account_number']}' LIMIT 1";
                                                    $userResult = mysqli_query($conn, $userQuery);
                                                    if ($userRow = mysqli_fetch_assoc($userResult)) {
                                                        $landlord_name = htmlspecialchars($userRow['first_name'] . ' ' . $userRow['last_name']);
                                                    }
                                                }

                                                echo "<tr>";
                                                echo "<td>{$counter}</td>";
                                                echo "<td>" . htmlspecialchars($row['property_code']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['street']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['city'] . ', ' . $row['province']) . "</td>";
                                                echo "<td>" . $landlord_name . "</td>";
                                                echo "<td>3</td>";
                                                echo "<td>" . date('m/d/y', strtotime($row['date_created'])) . "</td>";
                                                echo "<td>
                                                        <a href='view_property.php?id={$row['intake_id']}' class='btn btn-sm btn-info'>
                                                            <i class='fas fa-eye'></i>
                                                        </a>
                                                        <a href='javascript:void(0);' class='btn btn-sm btn-success approve-btn' data-id='{$row['intake_id']}'>
                                                            <i class='fas fa-check'></i>
                                                        </a>
                                                    </td>";

                                                echo "</tr>";
                                                $counter++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='10' class='text-center text-muted'>No Incomplete properties found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <!-- Incomplete Properties -->
                            <div class="tab-pane fade" id="Incomplete" role="tabpanel">
                                <div class="table-responsive mt-3">
                                    <table id="incompletePropertiesTable" class="table table-bordered table-striped table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Property Code</th>
                                                <th>Property Title</th>
                                                <th>Type</th>
                                                <th>Address</th>
                                                <th>City, State</th>
                                                <th>Landlord</th>
                                                <th>Submitted</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT p.intake_id, p.property_code, p.property_title, p.property_type, p.street, p.city, p.province, p.date_created, p.account_number
                                                    FROM property p
                                                    WHERE p.is_approve = 0
                                                    ORDER BY p.date_created DESC";
                                            $result = mysqli_query($conn, $query);
                                            $counter = 1;
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    // Fetch landlord name
                                                    $landlord_name = '';
                                                    if (!empty($row['account_number'])) {
                                                        $userQuery = "SELECT first_name, last_name FROM users WHERE account_number = '{$row['account_number']}' LIMIT 1";
                                                        $userResult = mysqli_query($conn, $userQuery);
                                                        if ($userRow = mysqli_fetch_assoc($userResult)) {
                                                            $landlord_name = htmlspecialchars($userRow['first_name'] . ' ' . $userRow['last_name']);
                                                        }
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>{$counter}</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_code']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
                                                    echo "<td >" . htmlspecialchars($row['street']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['city'] . ', ' . $row['province']) . "</td>";
                                                    echo "<td>" . $landlord_name . "</td>";
                                                    echo "<td>" . date('m/d/y', strtotime($row['date_created'])) . "</td>";
                                                    echo "<td>
                                                            <a href='view_property.php?id={$row['intake_id']}' class='btn btn-sm btn-info'>
                                                                <i class='fas fa-eye'></i>
                                                            </a>
                                                            <a href='approve_property.php?id={$row['intake_id']}' class='btn btn-sm btn-success'>
                                                                <i class='fas fa-check'></i>
                                                            </a>
                                                        </td>";
                                                    echo "</tr>";
                                                    $counter++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center text-muted'>No Incomplete properties found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- Listed Properties -->
                            <div class="tab-pane fade" id="Listed" role="tabpanel">
                                   <table id="listedPropertiesTable" class="table table-bordered table-striped table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Property Code</th>
                                                <th>Property Title</th>
                                                <th>Type</th>
                                                <th>Address</th>
                                                <th>City, State</th>
                                                <th>Landlord</th>
                                                <th>Incomplete</th>
                                                <th>Submitted</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT p.intake_id, p.property_code, p.property_title, p.property_type, p.street, p.city, p.province, p.date_created, p.account_number
                                                    FROM property p
                                                    WHERE p.is_approve = 1
                                                    ORDER BY p.date_created DESC";
                                            $result = mysqli_query($conn, $query);
                                            $counter = 1;
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    // Fetch landlord name
                                                    $landlord_name = '';
                                                    if (!empty($row['account_number'])) {
                                                        $userQuery = "SELECT first_name, last_name FROM users WHERE account_number = '{$row['account_number']}' LIMIT 1";
                                                        $userResult = mysqli_query($conn, $userQuery);
                                                        if ($userRow = mysqli_fetch_assoc($userResult)) {
                                                            $landlord_name = htmlspecialchars($userRow['first_name'] . ' ' . $userRow['last_name']);
                                                        }
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>{$counter}</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_code']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['street']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['city'] . ', ' . $row['province']) . "</td>";
                                                    echo "<td>" . $landlord_name . "</td>";
                                                    echo "<td>3</td>";
                                                    echo "<td>" . date('m/d/y', strtotime($row['date_created'])) . "</td>";
                                                    echo "<td>
                                                        <div class='d-flex flex-nowrap gap-1'>
                                                            <a href='view_property.php?id={$row['intake_id']}' class='btn btn-sm btn-info'>
                                                                <i class='fas fa-eye'></i>
                                                            </a>
                                                        </div>
                                                    </td>";
                                                    echo "</tr>";
                                                    $counter++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='10' class='text-center text-muted'>No Approved Properties found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
    // Define column configurations for each table
    const activeColumns = [
        null, // #
        null, // Property Code
        null, // Property Title
        null, // Type
        null, // Address
        null, // City, State
        null, // Landlord
        null, // Incomplete
        { type: "date" }, // Submitted
        { orderable: false } // Actions
    ];

    const incompleteColumns = [
        null, // #
        null, // Property Code
        null, // Property Title
        null, // Type
        null, // Address
        null, // City, State
        null, // Landlord
        { type: "date" }, // Submitted
        { orderable: false } // Actions
    ];

    const listedColumns = [
        null, // #
        null, // Property Code
        null, // Property Title
        null, // Type
        null, // Address
        null, // City, State
        null, // Landlord
        null, // Incomplete
        { type: "date" }, // Submitted
        { orderable: false } // Actions
    ];

    // Initialize DataTables for each table with explicit columns
    const activeTable = $('#activePropertiesTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 20, 50],
        "order": [[8, "desc"]], // Sort by Submitted column
        "columns": activeColumns,
        "language": { 
            "search": "Search Property:",
            "emptyTable": "No properties found in this category",
            "zeroRecords": "No matching properties found"
        },
        "drawCallback": function(settings) {
            // Force column count detection
            this.api().columns.adjust();
        }
    });

    const incompleteTable = $('#incompletePropertiesTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 20, 50],
        "order": [[7, "desc"]], // Sort by Submitted column
        "columns": incompleteColumns,
        "language": { 
            "search": "Search Property:",
            "emptyTable": "No incomplete properties found",
            "zeroRecords": "No matching properties found"
        },
        "drawCallback": function(settings) {
            this.api().columns.adjust();
        }
    });

    const listedTable = $('#listedPropertiesTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 20, 50],
        "order": [[8, "desc"]], // Sort by Submitted column
        "columns": listedColumns,
        "language": { 
            "search": "Search Property:",
            "emptyTable": "No listed properties found",
            "zeroRecords": "No matching properties found"
        },
        "drawCallback": function(settings) {
            this.api().columns.adjust();
        }
    });

    // Alternative initialization method for empty tables
    function safeDataTableInit(tableId, columns, orderColumn) {
        const table = $('#' + tableId);
        const tbody = table.find('tbody');
        
        // Check if table has data rows (excluding the "no data" message row)
        const hasData = tbody.find('tr').length > 0 && 
                       !tbody.find('tr').first().find('td').hasClass('text-muted');
        
        if (hasData) {
            return table.DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 20, 50],
                "order": [[orderColumn, "desc"]],
                "columns": columns,
                "language": { 
                    "search": "Search Property:",
                    "emptyTable": "No properties found",
                    "zeroRecords": "No matching properties found"
                }
            });
        } else {
            // For empty tables, use a simpler initialization
            return table.DataTable({
                "searching": false,
                "ordering": false,
                "info": false,
                "paging": false,
                "language": {
                    "emptyTable": "No properties found in this category"
                }
            });
        }
    }

    // Re-initialize tables using safe method
    $('#activePropertiesTable').DataTable().destroy();
    $('#incompletePropertiesTable').DataTable().destroy();
    $('#listedPropertiesTable').DataTable().destroy();

    const safeActiveTable = safeDataTableInit('activePropertiesTable', activeColumns, 8);
    const safeIncompleteTable = safeDataTableInit('incompletePropertiesTable', incompleteColumns, 7);
    const safeListedTable = safeDataTableInit('listedPropertiesTable', listedColumns, 8);

    // Redraw when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Small delay to ensure tab transition is complete
        setTimeout(function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 100);
    });

    // SweetAlert delete confirmation
    $('.delete-btn').on('click', function () {
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

    // Add custom styling to DataTables
    $('.dataTables_filter input').addClass('form-control form-control-sm');
    $('.dataTables_length select').addClass('form-control form-control-sm');
});

// Approve property via AJAX
$('.approve-btn').on('click', function() {
    const id = $(this).data('id');
    $.ajax({
        url: '../functions/approve_property.php',
        type: 'POST',
        data: { id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Show toast
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000, // shorter timer for faster refresh
                    timerProgressBar: true,
                    didClose: () => {
                        // Refresh page after toast closes
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        },
        error: function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'An error occurred.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
});

</script>

</body>
</html>