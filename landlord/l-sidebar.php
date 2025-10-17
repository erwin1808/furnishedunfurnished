<!-- Font Awesome + Lato -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

<style>/* ===== Sidebar Base ===== */
.sidebar {
    background-color: #ffffff !important;
    font-family: 'Lato', sans-serif !important;
    border-right: 1px solid #e3e6f0;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow-x: hidden; /* Prevent overflow */
}

/* ===== Brand Logo ===== */
.sidebar .sidebar-brand img {
    height: 60px;
    width: auto;
    transition: transform 0.3s ease;
}


/* ===== Headings ===== */
.sidebar .sidebar-heading {
    font-size: 1.1rem !important;
    text-transform: uppercase;
    font-weight: 700;
    color: #00524e !important;
    letter-spacing: 0.5px;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

/* ===== Nav Links ===== */
.sidebar .nav-link {
    color: #00524e !important;
    font-size: 1.1rem;
    font-weight: 400;
    padding: 12px 18px; /* adjusted for better fit */
    border-radius: 10px;
    margin: 6px 10px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    box-sizing: border-box; /* keeps shadow inside width */
}
.sidebar .nav-link i {
    font-size: 1.3rem !important;
    margin-right: 10px;
    color: #00524e !important;
    transition: color 0.3s ease;
}

/* ===== Hover & Active States ===== */
.sidebar .nav-link:hover {
    background-color: #e6f5f4 !important;
    color: #00524e !important;
    transform: translateX(2px); /* subtle move */
    box-shadow: 0 2px 6px rgba(0, 82, 78, 0.1);
}
.sidebar .nav-link:hover i {
    color: #00524e !important;
}

/* Active item (current page) */
.sidebar .nav-item.active .nav-link {
    background-color: #d8efee !important;
    color: #00524e !important;
    font-weight: 600;
    box-shadow: inset 4px 0 0 #00524e;
    margin-right: 10px; /* keeps it inside width */
    transform: none !important;
}
.sidebar .nav-item.active .nav-link i {
    color: #00524e !important;
}

/* ===== Divider ===== */
.sidebar hr.sidebar-divider {
    border-top: 1px solid #e3e6f0;
    margin: 1rem 1rem;
}

/* ===== Sidebar Toggler ===== */
#sidebarToggle {
    background-color: #00524e;
    color: #fff;
    transition: all 0.3s ease;
}
#sidebarToggle:hover {
    background-color: #007b75;
}/* ===== Hide elements when sidebar is collapsed ===== */
.sidebar.toggled .sidebar-brand {
    display: none !important; /* hides the logo entirely */
}

.sidebar.toggled .sidebar-heading {
    display: none !important; /* hides section titles */
}

.sidebar.toggled .nav-link {
    justify-content: center;
    padding: 12px 0 !important;
    margin-left: 0;
}

.sidebar.toggled .nav-link i {
    margin-right: 0 !important;
    font-size: 1.4rem !important;
}

</style>


<!-- Sidebar -->
<ul class="navbar-nav bg-white sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <img src="../images/full-logo.png" alt="Property Manager Logo" style="height: 60px; width: auto;">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Properties</div>

    <!-- Nav Item - Properties -->
    <li class="nav-item">
        <a class="nav-link" href="l-properties.php">
            <i class="fas fa-building"></i>
            <span>My Properties</span></a>
    </li>

    <!-- Nav Item - Add Property -->
    <li class="nav-item">
        <a class="nav-link" href="add-property.php">
            <i class="fas fa-plus-circle"></i>
            <span>Add Property</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Tenants</div>

    <!-- Nav Item - Tenants -->
    <li class="nav-item">
        <a class="nav-link" href="tenants.php">
            <i class="fas fa-users"></i>
            <span>Tenants</span></a>
    </li>

    <!-- Nav Item - Applications -->
    <li class="nav-item">
        <a class="nav-link" href="applications.php">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Financial</div>

    <!-- Nav Item - Payments -->
    <li class="nav-item">
        <a class="nav-link" href="payments.php">
            <i class="fas fa-money-bill-wave"></i>
            <span>Payments</span></a>
    </li>

    <!-- Nav Item - Reports -->
    <li class="nav-item">
        <a class="nav-link" href="reports.php">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
