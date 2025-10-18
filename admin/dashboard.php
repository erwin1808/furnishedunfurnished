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
    
    <!-- Fix for chart overlapping -->
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .chart-area, .chart-pie, .chart-bar {
            position: relative !important;
            height: 100% !important;
            width: 100% !important;
        }
        
        .chart-area canvas, 
        .chart-pie canvas, 
        .chart-bar canvas {
            max-height: 100% !important;
            width: 100% !important;
        }
        
        /* Ensure proper spacing between chart sections */
        .card.shadow.mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        /* Fix for pie chart container */
        .chart-pie.pt-4.pb-2 {
            height: 250px !important;
        }
        
        /* Common Searches Styles */
        .search-tag {
            display: inline-block;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 6px 12px;
            margin: 4px 6px;
            font-size: 13px;
            color: #495057;
            transition: all 0.3s ease;
        }
        
        .search-tag:hover {
            background: #e9ecef;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-count {
            background: #3b1453;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: 6px;
        }
        
        .trending-up {
            color: #28a745;
            font-size: 12px;
        }
        
        .trending-down {
            color: #dc3545;
            font-size: 12px;
        }
        
        .location-card {
            border-left: 4px solid #3b1453;
        }
    </style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
    <?php include ("../includes/sidenav.php");?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include ("../includes/topnav.php");?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row - Cards -->
                    <div class="row">
                        <!-- Total Properties -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Properties</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Occupied Properties -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Occupied Properties</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-home fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Revenue -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monthly Revenue</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$8,240</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Applications -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Applications</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row - Charts -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Property Types</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Furnished</span>
                                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Unfurnished</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Analytics Section -->
                    <div class="row">
                        <!-- Most Popular Locations -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Most Popular Locations
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container mb-4">
                                        <canvas id="locationsChart"></canvas>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Location</th>
                                                    <th>Properties</th>
                                                    <th>Views</th>
                                                    <th>Trend</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Downtown Core</td>
                                                    <td>15</td>
                                                    <td>1,240</td>
                                                    <td><i class="fas fa-arrow-up trending-up"></i> 12%</td>
                                                </tr>
                                                <tr>
                                                    <td>Westside District</td>
                                                    <td>8</td>
                                                    <td>980</td>
                                                    <td><i class="fas fa-arrow-up trending-up"></i> 8%</td>
                                                </tr>
                                                <tr>
                                                    <td>Riverside Area</td>
                                                    <td>12</td>
                                                    <td>875</td>
                                                    <td><i class="fas fa-arrow-down trending-down"></i> 3%</td>
                                                </tr>
                                                <tr>
                                                    <td>North Hills</td>
                                                    <td>6</td>
                                                    <td>720</td>
                                                    <td><i class="fas fa-arrow-up trending-up"></i> 15%</td>
                                                </tr>
                                                <tr>
                                                    <td>Eastgate</td>
                                                    <td>9</td>
                                                    <td>650</td>
                                                    <td><i class="fas fa-arrow-up trending-up"></i> 5%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Common Searches -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4 location-card">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-search mr-1"></i> Common Search Terms
                                    </h6>
                                    <span class="badge badge-primary">Last 30 Days</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-dark mb-3">Top Search Locations</h6>
                                        <div class="search-tags-container">
                                            <span class="search-tag">
                                                Downtown Apartments <span class="search-count">284</span>
                                            </span>
                                            <span class="search-tag">
                                                Studio Near University <span class="search-count">192</span>
                                            </span>
                                            <span class="search-tag">
                                                Pet Friendly Houses <span class="search-count">167</span>
                                            </span>
                                            <span class="search-tag">
                                                Luxury Condos <span class="search-count">145</span>
                                            </span>
                                            <span class="search-tag">
                                                Furnished Apartments <span class="search-count">138</span>
                                            </span>
                                            <span class="search-tag">
                                                Waterfront Properties <span class="search-count">126</span>
                                            </span>
                                            <span class="search-tag">
                                                Short Term Rentals <span class="search-count">112</span>
                                            </span>
                                            <span class="search-tag">
                                                Family Homes <span class="search-count">98</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-dark mb-3">Popular Amenities</h6>
                                        <div class="search-tags-container">
                                            <span class="search-tag">
                                                Parking Included <span class="search-count">356</span>
                                            </span>
                                            <span class="search-tag">
                                                Gym Access <span class="search-count">287</span>
                                            </span>
                                            <span class="search-tag">
                                                Swimming Pool <span class="search-count">234</span>
                                            </span>
                                            <span class="search-tag">
                                                Balcony <span class="search-count">198</span>
                                            </span>
                                            <span class="search-tag">
                                                In-unit Laundry <span class="search-count">176</span>
                                            </span>
                                            <span class="search-tag">
                                                Air Conditioning <span class="search-count">154</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-dark mb-3">Price Range Searches</h6>
                                        <div class="search-tags-container">
                                            <span class="search-tag">
                                                Under $1,000 <span class="search-count">189</span>
                                            </span>
                                            <span class="search-tag">
                                                $1,000 - $1,500 <span class="search-count">267</span>
                                            </span>
                                            <span class="search-tag">
                                                $1,500 - $2,000 <span class="search-count">312</span>
                                            </span>
                                            <span class="search-tag">
                                                $2,000 - $3,000 <span class="search-count">198</span>
                                            </span>
                                            <span class="search-tag">
                                                Over $3,000 <span class="search-count">87</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="font-weight-bold text-dark mb-3">Emerging Trends</h6>
                                        <div class="alert alert-info">
                                            <small>
                                                <i class="fas fa-lightbulb mr-2"></i>
                                                <strong>Insight:</strong> Searches for "home office space" increased by 45% this month. 
                                                Consider highlighting remote work amenities in your listings.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row - Tables -->
                    <div class="row">
                        <!-- Recent Properties -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Properties</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Property Name</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Rent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>Sunset Apartments #5B</td><td>Apartment</td><td>Occupied</td><td>$1,200</td></tr>
                                                <tr><td>Garden Villa #2</td><td>House</td><td>Vacant</td><td>$1,800</td></tr>
                                                <tr><td>Downtown Loft #12</td><td>Studio</td><td>Occupied</td><td>$950</td></tr>
                                                <tr><td>Riverside Condo #8A</td><td>Condominium</td><td>Maintenance</td><td>$1,350</td></tr>
                                                <tr><td>Mountain View House</td><td>House</td><td>Occupied</td><td>$2,100</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Applications -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Applications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Applicant</th>
                                                    <th>Property</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>Sarah Johnson</td><td>Garden Villa #2</td><td>2023-12-10</td><td><span class="badge badge-warning">Pending</span></td></tr>
                                                <tr><td>Michael Chen</td><td>Downtown Loft #12</td><td>2023-12-08</td><td><span class="badge badge-success">Approved</span></td></tr>
                                                <tr><td>Emma Wilson</td><td>Riverside Condo #8A</td><td>2023-12-05</td><td><span class="badge badge-danger">Rejected</span></td></tr>
                                                <tr><td>James Rodriguez</td><td>Sunset Apartments #5B</td><td>2023-12-03</td><td><span class="badge badge-warning">Pending</span></td></tr>
                                                <tr><td>Olivia Parker</td><td>Mountain View House</td><td>2023-11-29</td><td><span class="badge badge-success">Approved</span></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto text-center">
                    <span>Copyright &copy; Furnished/Unfurnished Property Management 2023</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts -->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Chart.js -->
    <script src="../assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Charts Initialization -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit to ensure DOM is fully rendered
        setTimeout(function() {
            // ----- Area Chart -----
            const ctxArea = document.getElementById("myAreaChart");
            if (ctxArea) {
                new Chart(ctxArea, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                        datasets: [{
                            label: "Revenue ($)",
                            data: [4000, 5000, 4500, 6000, 5500, 7000, 6500],
                            backgroundColor: "rgba(59, 20, 83, 0.2)",
                            borderColor: "#3b1453",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { 
                                display: false 
                            } 
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true 
                            } 
                        }
                    }
                });
            }

            // ----- Pie Chart -----
            const ctxPie = document.getElementById("myPieChart");
            if (ctxPie) {
                new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: ["Furnished", "Unfurnished"],
                        datasets: [{
                            data: [8, 4],
                            backgroundColor: ["#3b1453", "#4f145c"]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom' 
                            }
                        }
                    }
                });
            }

            // ----- Locations Chart -----
            const ctxLocations = document.getElementById("locationsChart");
            if (ctxLocations) {
                new Chart(ctxLocations, {
                    type: 'bar',
                    data: {
                        labels: ["Downtown Core", "Westside", "Riverside", "North Hills", "Eastgate"],
                        datasets: [{
                            label: 'Property Views',
                            data: [1240, 980, 875, 720, 650],
                            backgroundColor: '#3b1453',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            title: { 
                                display: true, 
                                text: 'Property Views by Location' 
                            }
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                ticks: { 
                                    precision:0 
                                } 
                            } 
                        }
                    }
                });
            }
        }, 100);
    });
    </script>
</body>
</html>