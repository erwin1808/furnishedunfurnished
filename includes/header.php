<?php
//header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include your functions file
include 'db.php';
/**
 * Get user data by email
 */
function getUserInfoByEmail($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ? AND is_deleted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Fetch logged-in user data
$user = null;
if (!empty($_SESSION['email'])) {
    $user = getUserInfoByEmail($conn, $_SESSION['email']);
}

// Set user-related variables
$defaultImage = "images/default-avatar-icon-of-social-media-user-vector.jpg";
$fullName = $user ? htmlspecialchars(ucwords(strtolower($user['first_name'] . ' ' . $user['last_name']))) : "Guest";
$accountNumber = '';

if (isset($_SESSION['userid'])) {
    // Fetch account number
    $userid = $_SESSION['userid'];
    $stmt = $conn->prepare("SELECT account_number FROM users WHERE userid = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($accountNumber);
    $stmt->fetch();
    $stmt->close();
    
    // Fetch user info if email is in session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $user = getUserInfoByEmail($conn, $email);
        
        // Set user image and name
        $defaultImage = "../img/undraw_profile.svg";
        $imageSrc = !empty($user['image']) ? "../images/" . htmlspecialchars($user['image']) : $defaultImage;
        $fullName = $user ? htmlspecialchars(ucwords(strtolower($user['first_name'] . ' ' . $user['last_name']))) : "Guest";
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--header.php-->
<style>
    :root {
    --primary: #0056b3;
    --primary-dark: #003d7a;
    --secondary: #e67e22;
    --secondary-dark: #d35400;
    --dark: #1a252f;
    --light: #f8f9fa;
    --gray: #6c757d;
    --light-gray: #e9ecef;
    --white: #ffffff;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    color: #333;
    line-height: 1.6;
    background-color: var(--light);
    overflow-x: hidden;
}

/* Disable underline animation on nav-item nav-link only */
.nav-item .nav-link::after {
    display: none;
}

/* Nav Links */
.nav-link {
    color: var(--dark);
    text-decoration: none;
    font-weight: 500;
    font-size: 1.1rem;
    position: relative;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 5px;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary);
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary);
    transition: var(--transition);
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

/* Repair button styling - reduced size */
.repair-btn {
    background-color: #0056b3;
    color: white;
    padding: 6px 10px; /* Smaller padding */
    border-radius: 14px; /* Slightly smaller, still rounded */
    font-size: 0.85rem; /* Smaller font */
    font-weight: 600;
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    display: inline-block;
    border: 2px solid transparent;
    text-decoration: none !important;
    position: relative;
}

/* Remove underline and hover underline effect */
.repair-btn::after {
    content: none !important;
}

.repair-btn:hover,
.repair-btn:focus,
.repair-btn.active {
    background-color: white;
    color: #0056b3;
    border-color: #0056b3;
    text-decoration: none !important;
    box-shadow: 0 0 5px rgba(0, 86, 179, 0.4);
}


.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Section */
.store-header {
    background-color: var(--white);
    padding: 15px 0;
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: var(--transition);
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.store-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary);
    display: flex;
    align-items: center;
}

.store-name i {
    margin-right: 10px;
    color: var(--secondary);
}

.nav-links {
    display: flex;
    gap: 25px;
}

.cart-count {
    background-color: var(--secondary);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    margin-left: 5px;
}

.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--dark);
    cursor: pointer;
}

/* User dropdown styles */
.navbar-nav {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    position: relative;
    list-style: none;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    display: none;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    padding: 10px 0;
    z-index: 1001;
    width: 220px;
}

.dropdown-list {
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    padding: 8px 20px;
    display: block;
    color: #1a252f;
    text-decoration: none;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.img-profile {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
}


.badge-counter {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 0.7rem;
}



/* Responsive Styles */
@media (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
    }

    .nav-links {
        position: fixed;
        top: 80px;
        left: -100%;
        width: 100%;
        height: calc(100vh - 80px);
        background-color: var(--white);
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 40px 20px;
        gap: 30px;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .nav-links.active {
        left: 0;
    }
    
    .dropdown-menu {
        position: static;
        width: 100%;
        box-shadow: none;
    }
}

@media (max-width: 576px) {
    .store-name {
        font-size: 1.5rem;
    }
}
.repair-btn-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .repair-btn-group {
        flex-direction: column;
        align-items: stretch;
    }
}

</style>

<body>
<?php 
    // Detect current page filename
    $currentPage = basename($_SERVER['PHP_SELF']); 
?>

<!-- Header Section -->
<header class="store-header">
    <div class="container header-container">
      <div class="store-name">
    <img src="../images/odr_logo.png" alt="ODR Logo" style="height: 34px; vertical-align: middle; margin-right: 8px;">
    Ohio Dental Repair
</div>


        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        
        <nav class="nav-links" id="navLinks">
            <a class="nav-link <?php echo ($currentPage == 'landing.php') ? 'active' : ''; ?>" href="../home/landing.php">Home</a>

            <?php if (!isset($_SESSION['userid'])): ?>
                <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="../login/index.php">Login</a>
                <a class="nav-link <?php echo ($currentPage == 'register.php') ? 'active' : ''; ?>" href="../register/register.php">Register</a>
            <?php endif; ?>

            <a class="nav-link <?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>" href="../about/about.php">About</a>
            <a class="nav-link <?php echo ($currentPage == 'services.php') ? 'active' : ''; ?>" href="../services/services.php">Services</a>
            <a class="nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>" href="../contact/contact.php">Contact</a>
             <?php if (!isset($_SESSION['userid'])): ?>
      <div class="repair-btn-group">


    <!-- Mail-in -->
    <a class="nav-link repair-btn <?php echo ($currentPage == 'mailin.php' || $currentPage == 'mailin_user.php') ? 'active' : ''; ?>" 
       href="<?php echo isset($_SESSION['userid']) ? '../mailin/mailin_user.php' : '../mailin/mailin.php'; ?>">
       📦 <small>General Shipping Label</small>
    </a>


</div>
 <?php endif; ?>

            <?php if (isset($_SESSION['userid'])): ?>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600"><?php echo $accountNumber; ?></span>
                        <img class="img-profile rounded-circle" src="<?php echo $imageSrc; ?>">
                    </a>
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
     aria-labelledby="userDropdown">
     
     <?php if (isset($_SESSION['userid'])): ?>

        <!-- Repair Request -->
        <a class="dropdown-item <?php echo ($currentPage == 'request.php' || $currentPage == 'request_user.php') ? 'active' : ''; ?>" 
           href="../request/request_user.php">
           <i class="fas fa-tools"></i>
 Repair Request
        </a>

   
    <?php endif; ?>



    <a class="dropdown-item" href="../client/myorders.php" style="font-size: 16px;">
        <i class="fas fa-box fa-sm fa-fw mr-2 text-gray-400"></i>
        Open Orders
    </a>
    <a class="dropdown-item" href="../client/userprofile.php">
        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
        Profile
    </a>
    <a class="dropdown-item" href="../client/changepassword.php" style="font-size: 16px;">
        <i class="fas fa-shield-alt fa-sm fa-fw mr-2 text-gray-400"></i>
        Change Password
    </a>

    <a class="dropdown-item" href="javascript:void(0);" onclick="showLogoutModal()">
        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
        Logout
    </a>

</div>


                </li>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    // DOM Elements
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Mobile menu toggle
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    if (navLinks.classList.contains('active')) {
                        toggleMobileMenu();
                    }
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.store-header');
            if (window.scrollY > 50) {
                header.style.padding = '10px 0';
                header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.padding = '15px 0';
                header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            }
        });

        // Dropdown toggle
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = userDropdown?.nextElementSibling;

        if (userDropdown && dropdownMenu) {
            userDropdown.addEventListener('click', (e) => {
                e.preventDefault();
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        }
    });

    // Toggle mobile menu visibility
    function toggleMobileMenu() {
        navLinks.classList.toggle('active');
        mobileMenuBtn.innerHTML = navLinks.classList.contains('active')
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    }

    // Show logout confirmation modal
    function showLogoutModal() {
        Swal.fire({
            title: 'Ready to Leave?',
            text: "Select 'Logout' below if you are ready to end your current session.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../logout.php';
            }
        });
    }

    // Notification functionality
    function fetchNotifications() {
        $.ajax({
            url: 'fetch_notifications.php',
            method: 'POST',
            success: function (response) {
                const data = $(response).find('notification');
                let notificationsHtml = '';
                let unreadCount = 0;

                data.each(function () {
                    const notification = $(this);
                    const id = notification.find('id').text();
                    const message = notification.find('message').text();
                    const url = notification.find('url').text();
                    const isRead = notification.find('is_read').text();
                    const createdAt = notification.find('created_at').text();

                    unreadCount += isRead === "0" ? 1 : 0;

                    const notificationClass = isRead === "0" ? '' : 'bg-light text-muted';

                    notificationsHtml += `
                        <a class="dropdown-item d-flex align-items-center ${notificationClass}" href="${url}" data-id="${id}">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">${createdAt}</div>
                                <span class="${isRead === "0" ? 'font-weight-bold' : ''}">
                                    ${message}
                                </span>
                            </div>
                        </a>`;
                });

                $('#notification-list').html(notificationsHtml || '<div class="dropdown-item text-center small text-gray-500">No new notifications</div>');
                $('#notification-badge').text(unreadCount > 0 ? unreadCount : '');
            }
        });
    }

    // Poll notifications every 2 seconds
    if (typeof $ !== 'undefined') {
        setInterval(fetchNotifications, 2000);
        fetchNotifications();

        // Mark notification as read when clicked
        $('#notification-list').on('click', '.dropdown-item', function () {
            const notificationId = $(this).data('id');
            const notificationElement = $(this);

            $.ajax({
                url: 'mark_as_read.php',
                method: 'POST',
                data: { notification_id: notificationId },
                success: function () {
                    notificationElement.addClass('bg-light text-muted');
                },
                error: function () {
                    alert('Error marking notification as read.');
                }
            });
        });
    }
</script>