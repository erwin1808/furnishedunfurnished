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
$logged_in_user_id = $_SESSION['userid'];

// Check if user ID is set in the session
if (!isset($logged_in_user_id)) {
    header('Location: ../login/index.php');
    exit();
}

// Function to get repair tracking data
function getRepairTrackingData($conn, $user_id) {
    $query = "SELECT * FROM repairs ORDER BY received_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$repairs = getRepairTrackingData($conn, $logged_in_user_id);

function getClientData($conn) {
    $query = "SELECT * FROM users ORDER BY last_name ASC";
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
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="Dental Equipment Repair">
    <meta name="keywords" content="<?= $seo_keywords; ?>">
    <meta property="og:title" content="Dental Equipment Repair">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">

    <title>Dental Equipment Repair Dashboard</title>
    <?php include "../includes/icon.php"; ?>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Dashboard CSS -->
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            grid-column: span 3;
        }

        .main-chart {
            grid-column: span 8;
        }

        .side-chart {
            grid-column: span 4;
        }

        .half-width-chart {
            grid-column: span 6;
        }

        @media (max-width: 1200px) {
            .stat-card {
                grid-column: span 6;
            }
            
            .main-chart {
                grid-column: span 12;
            }
            
            .side-chart {
                grid-column: span 12;
            }
            
            .half-width-chart {
                grid-column: span 12;
            }
        }

        @media (max-width: 768px) {
            .stat-card {
                grid-column: span 12;
            }
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
                    <!-- Stats Overview Cards -->
                    <div class="dashboard-grid">
                        <!-- Total Repairs Card -->
                        <div class="card border-left-primary shadow h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Repairs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= count($repairs) ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Repairs Card -->
                        <div class="card border-left-warning shadow h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Repairs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= array_reduce($repairs, function($carry, $item) {
                                                return $carry + ($item['status'] === 'Pending' ? 1 : 0);
                                            }, 0) ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- In Progress Repairs Card -->
                        <div class="card border-left-info shadow h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            In Progress</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= array_reduce($repairs, function($carry, $item) {
                                                return $carry + ($item['status'] === 'In Progress' ? 1 : 0);
                                            }, 0) ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Repairs Card -->
                        <div class="card border-left-success shadow h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Completed</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= array_reduce($repairs, function($carry, $item) {
                                                return $carry + ($item['status'] === 'Completed' ? 1 : 0);
                                            }, 0) ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Repair Status Chart -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Repair Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                                <canvas id="repairStatusChart"></canvas>
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

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
        $(document).ready(function() {
            // Repair Status Chart
            const repairStatusCtx = document.getElementById('repairStatusChart').getContext('2d');
            const repairStatusChart = new Chart(repairStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed', 'Shipped'],
                    datasets: [{
                        data: [
                            <?= array_reduce($repairs, function($carry, $item) { return $carry + ($item['status'] === 'Pending' ? 1 : 0); }, 0) ?>,
                            <?= array_reduce($repairs, function($carry, $item) { return $carry + ($item['status'] === 'In Progress' ? 1 : 0); }, 0) ?>,
                            <?= array_reduce($repairs, function($carry, $item) { return $carry + ($item['status'] === 'Completed' ? 1 : 0); }, 0) ?>,
                            <?= array_reduce($repairs, function($carry, $item) { return $carry + ($item['status'] === 'Shipped' ? 1 : 0); }, 0) ?>
                        ],
                        backgroundColor: [
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(23, 162, 184, 0.8)',
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(0, 123, 255, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 193, 7, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(0, 123, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        });
    </script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>