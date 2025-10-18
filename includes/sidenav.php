<?php 
//sidenav.php
// Check if session is not already started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current filename without the ".php" extension
$currentPage = basename($_SERVER['PHP_SELF'], ".php");
?>

<style>
    /* Add this to your existing sidebar styles */
.sidebar.toggled .nav-item .collapse {
    display: none;
}

.sidebar.toggled .nav-item .collapse.show {
    display: block;
}

.sidebar .nav-item .collapse {
    position: relative;
    left: 0;
    z-index: 1;
    top: 0;
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
    -webkit-animation-duration: 0.3s;
    animation-duration: 0.3s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}

@-webkit-keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}

@keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}
    /* Sidebar background color */
    .sidebar {
        background-color: #002147 !important; 
        
    transition: width 0.3s ease; /* Smooth transition for width */

    }

    /* Active nav item style */
    .nav-item.active .nav-link {
        background-color: #f8f9fc;
        color: #2c0c64 !important; 
        border-radius: 8px 0 0 8px; 
        position: relative;
    }

  
    .nav-item.active .nav-link::after {
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

    /* Active icon color */
    .nav-item.active .nav-link i {
        color: #2c0c64 !important; 
    }

    /* Default nav link color */
    .nav-link {
        color: #ffffff; 
    }

    /* Hide accordion arrow */
    .nav-link.collapsed::after {
        display: none !important; 
    }
    
</style>

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15"></div>
        <div class="sidebar-brand-text">
            <span>Admin</span>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item <?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

<!-- Pending Properties -->
<li class="nav-item <?= ($currentPage == 'inquiries' || $currentPage == 'inquiries_user') ? 'active' : '' ?>">
    <a class="nav-link" href="a-pending-properties.php">
      <i class="fas fa-comments"></i> <!-- Changed icon to comments/chat -->
      <span>Pending Properties</span>
    </a>
</li>


<!--Report Tickets-->
<li class="nav-item <?= ($currentPage == 'repair_tracking') ? 'active' : '' ?>">
    <a class="nav-link" href="#">
      <i class="fas fa-tools"></i> <!-- changed to tools icon -->
      <span>Report Tickets</span>
    </a>
</li>

<!-- Services 
<li class="nav-item <?= ($currentPage == 'services_offered') ? 'active' : '' ?>">
    <a class="nav-link" href="services_offered.php">
      <i class="fas fa-handshake"></i>
      <span>Services</span>
    </a>
</li>
-->

<!-- User Management (Admin Only) -->

    <li class="nav-item <?= ($currentPage == 'user_approval_management' || $currentPage == 'user_management' || $currentPage == 'admin_management') ? 'active' : '' ?>">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-users"></i>
            <span> User Management</span>
        </a>
    </li>

 <!--li class="nav-item <?= ($currentPage == 'user_logs') ? 'active' : '' ?>">
    <a class="nav-link" href="user_logs.php">
        <i class="fas fa-fw fa-history"></i>
        <span> User Logs</span>
    </a>
</li-->




    <!-- Information Management Accordion -->


        <!--div class="nav-item">
            <a class="nav-link collapsed <?= in_array($currentPage, ['info_management', 'info_management_contact', 'info_management_team', 'info_management_career', 'info_management_blogs' , 'info_management_training_videos' , 'info_management_featured_clients']) ? '' : '' ?>" 
               href="#" data-toggle="collapse" data-target="#infoManagement" aria-expanded="true" aria-controls="infoManagement">
                <i class="fas fa-fw fa-info-circle"></i>
                <span>Information Management</span>
            </a>
            <div id="infoManagement" 
                 class="collapse <?= in_array($currentPage, ['info_management', 'info_management_contact', 'info_management_team', 'info_management_career', 'info_management_blogs', 'info_management_training_videos' , 'info_management_featured_clients']) ? 'show' : '' ?>" 
                 aria-labelledby="headingInfoManagement" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Manage Information:</h6>
                    <a class="collapse-item <?= ($currentPage == 'info_management') ? 'active' : '' ?>" 
                       href="info_management.php">About Us</a>
                    <a class="collapse-item <?= ($currentPage == 'info_management_contact') ? 'active' : '' ?>" 
                       href="info_management_contact.php">Contact Info</a>
                    <a class="collapse-item <?= ($currentPage == 'info_management_team') ? 'active' : '' ?>" 
                       href="info_management_team.php">Team Members</a>
                       <a class="collapse-item <?= ($currentPage == 'info_management_career') ? 'active' : '' ?>" 
                       href="info_management_career.php">Career</a>
                       <a class="collapse-item <?= ($currentPage == 'info_management_blogs') ? 'active' : '' ?>" 
                       href="info_management_blogs.php">Blogs</a>
                       <a class="collapse-item <?= ($currentPage == 'info_management_training_videos') ? 'active' : '' ?>" 
                       href="info_management_training_videos.php">Training Videos</a>
                       <a class="collapse-item <?= ($currentPage == 'info_management_featured_clients') ? 'active' : '' ?>" 
                       href="info_management_featured_clients.php">Featured Clients</a>
                </div>
            </div>
        </div-->


    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>



<!-- JavaScript Code -->
<script>
$(document).ready(function() {
    // Toggle the side navigation
    $("#sidebarToggle").on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Add this line to prevent event bubbling
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
    });

    // Auto-collapse on mobile
    function autoCollapse() {
        if ($(window).width() < 768) {
            $(".sidebar").addClass("toggled");
        } else {
            $(".sidebar").removeClass("toggled");
        }
    }
    
    autoCollapse();
    $(window).resize(autoCollapse);

    // Prevent accordion clicks from toggling the sidebar
    $('.nav-link[data-toggle="collapse"]').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>