<!-- Font Awesome + Lato -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

<style>
/* =============================
   MODERNIZED DARK SIDEBAR (002147)
   ============================= */
.sidebar {
    background-color: #002147 !important;
    font-family: 'Lato', sans-serif !important;
    font-size: 1rem !important;
    transition: width 0.3s ease;
    overflow-x: hidden;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
}

/* ===== Brand Logo ===== */
.sidebar .sidebar-brand img {
    height: 45px;
    width: auto;
    transition: transform 0.3s ease;
}
.sidebar .sidebar-brand:hover img {
    transform: scale(1.05);
}

/* ===== Headings ===== */
.sidebar .sidebar-heading {
    font-size: 1rem !important;
    text-transform: uppercase;
    font-weight: 700;
    color: #d9e2f1 !important;
    letter-spacing: 0.5px;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

/* ===== Nav Links ===== */
.sidebar .nav-link {
    color: #ffffff !important;
    font-size: 1rem;
    font-weight: 400;
    padding: 12px 18px;
    border-radius: 8px 0 0 8px;
    margin: 6px 10px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}
.sidebar .nav-link i {
    font-size: 1rem !important;
    margin-right: 10px;
    color: #ffffff !important;
    transition: color 0.3s ease;
}

/* ===== Hover Effects ===== */
.sidebar .nav-link:hover {
    background-color: #003366 !important;
    color: #ffffff !important;
    transform: translateX(2px);
}
.sidebar .nav-link:hover i {
    color: #ffffff !important;
}

/* ===== Active Item ===== */
.sidebar .nav-item.active .nav-link {
    background-color: #f8f9fc !important;
    color: #2c0c64 !important;
    font-weight: 600;
    position: relative;
}
.sidebar .nav-item.active .nav-link::after {
    content: '';
    position: absolute;
    top: 0;
    right: -10px;
    width: 20px;
    height: 100%;
    border-radius: 50%;
    background-color: #f8f9fc;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
    z-index: -1;
}
.sidebar .nav-item.active .nav-link i {
    color: #2c0c64 !important;
}

/* ===== Divider ===== */
.sidebar hr.sidebar-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    margin: 1rem 1rem;
}

/* ===== Sidebar Toggler ===== */
#sidebarToggle {
    background-color: #f8f9fc;
    color: #002147;
    transition: all 0.3s ease;
}
#sidebarToggle:hover {
    background-color: #e2e6ea;
}

/* ===== Collapsed State ===== */
.sidebar.toggled .sidebar-brand {
    display: none !important;
}
.sidebar.toggled .sidebar-heading {
    display: none !important;
}
.sidebar.toggled .nav-link {
    justify-content: center;
    padding: 12px 0 !important;
    margin-left: 0;
}
.sidebar.toggled .nav-link i {
    margin-right: 0 !important;
    font-size: 1rem !important;
}
</style>

<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <img src="../images/new-logo.png" alt="Property Manager Logo">
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Properties -->
    <div class="sidebar-heading">Properties</div>

    <li class="nav-item">
        <a class="nav-link" href="l-properties.php">
            <i class="fas fa-building"></i>
            <span>My Properties</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-plus-circle"></i>
            <span>Add Property</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Tenants -->
    <div class="sidebar-heading">Tenants</div>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-users"></i>
            <span>Tenants</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Financial -->
    <div class="sidebar-heading">Financial</div>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-money-bill-wave"></i>
            <span>Payments</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- JS -->
<script>
$(document).ready(function() {
    $("#sidebarToggle").on('click', function(e) {
        e.preventDefault();
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
    });

    function autoCollapse() {
        if ($(window).width() < 768) {
            $(".sidebar").addClass("toggled");
        } else {
            $(".sidebar").removeClass("toggled");
        }
    }

    autoCollapse();
    $(window).resize(autoCollapse);
});
</script>
