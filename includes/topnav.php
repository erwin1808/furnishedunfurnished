<?php
// Include your functions file
include 'db.php'; 

// Start the session only if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fetch user information based on the session email.
 *
 * @param mysqli $conn The database connection object.
 * @param string $email The user's email from the session.
 * @return array|null User information or null if not found.
 */
function getUserInfoByEmail($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ? AND is_deleted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Fetch user information if the user is logged in
$user = null;
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $user = getUserInfoByEmail($conn, $email);
}

// Default image if none exists
$defaultImage = "../images/default-avatar-icon-of-social-media-user-vector.jpg"; // Change to your default image path
$imageSrc = !empty($user['image']) ? "../images/" . htmlspecialchars($user['image']) : $defaultImage;
$fullName = $user ? htmlspecialchars(ucwords(strtolower($user['first_name'] . ' ' . $user['last_name']))) : "Guest";
?>

<style>
    .btn-custom {
        background-color: #2c0c64; /* Custom color */
        color: white; /* Text color */
        border: none; /* Remove border */
    }

    .btn-custom:hover {
        background-color: #23095a; /* Darker shade for hover effect */
    }

    .icon-custom {
        color: #2c0c64; /* Custom color for icons */
    }

    .icon-custom:hover {
        color: #23095a; /* Darker shade for hover effect */
    }
</style>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
     <style>
    .logo {
    height: 100px; /* Set height to match the top bar */
    width: auto; /* Maintain aspect ratio */
    margin-right: 10px; /* Space between logo and text */
    margin-top: 10px;
}

.logo-text {
    color: #3b1350;
    font-size: 1.2em; /* Adjust font size as needed */
    font-weight: bold; /* Optional: Make the text bold */
    display: inline-block;
    vertical-align: middle; /* Align text with the logo */
    margin-top: 10px;
}

/* Make read notifications appear dull */
.bg-light.text-muted {
    background-color: #f0f0f0 !important;
    color: #6c757d !important;
}



     </style>
     
<?php
if (basename($_SERVER['PHP_SELF']) === 'admindb.php') {
    ?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <?php
}


if (basename($_SERVER['PHP_SELF']) === 'client_management.php') {
    ?>
    <script src="js/sb-admin-2.min.js"></script>
    <?php
}
?>

    



    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

<!-- Nav Item - Alerts>
<li class="nav-item dropdown no-arrow mx-1" id="alerts-container">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw icon-custom"></i>
        <span id="notification-badge" class="badge badge-danger badge-counter"></span>
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
         aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">Alerts Center</h6>
        <div id="notification-list" style="max-height: 300px; overflow-y: auto;">
    
        </div>
        <a id="show-all-alerts" class="dropdown-item text-center small text-gray-500" href="#" style="cursor: pointer;">
            
        </a>
    </div>
</li>


        
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw icon-custom"></i>
                <span class="badge badge-danger badge-counter">7</span>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">Message Center</h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                            problem I've been having.</div>
                        <div class="small text-gray-500">Emily Fowler �� 58m</div>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
        </li>
 -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 mt-1 d-none d-lg-inline text-gray-600 small"><?php echo $fullName; ?></span>
                <img class="img-profile rounded-circle" src="<?php echo $imageSrc; ?>">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="userprofile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="changepassword.php">
                   <i class="fas fa-shield-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                    Password and Security
                </a>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item" onclick="showLogoutModal()">
    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
    Logout
</button>
            </div>
        </li>

    </ul>

</nav>
<!-- Add SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showLogoutModal() {
        Swal.fire({
            title: 'Ready to Leave?',
            text: "Select 'Logout' below if you are ready to end your current session.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff', // Primary button color
            cancelButtonColor: '#6c757d', // Secondary button color
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../logout.php'; // Redirect to logout
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        // Function to fetch notifications
        function fetchNotifications() {
            $.ajax({
                url: 'fetch_notifications.php', // PHP script to fetch notifications
                method: 'POST',
                success: function (response) {
                    // Parse the XML response
                    const data = $(response).find('notification');
                    let notificationsHtml = '';
                    let unreadCount = 0;

                    // Iterate through each notification
                    data.each(function () {
                        const notification = $(this);
                        const id = notification.find('id').text();
                        const message = notification.find('message').text();
                        const url = notification.find('url').text();
                        const isRead = notification.find('is_read').text();
                        const createdAt = notification.find('created_at').text();

                        unreadCount += isRead === "0" ? 1 : 0;

                        // Add 'read' class if notification is read, otherwise 'unread' class
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

                    // Update the notification list and badge
                    $('#notification-list').html(notificationsHtml || '<div class="dropdown-item text-center small text-gray-500">No new notifications</div>');
                    $('#notification-badge').text(unreadCount > 0 ? unreadCount : '');
                }
            });
        }

        // Poll notifications every 2 seconds
        setInterval(fetchNotifications, 2000);
        fetchNotifications(); // Initial load

        // Mark notification as read when clicked
        $('#notification-list').on('click', '.dropdown-item', function () {
            const notificationId = $(this).data('id'); // Get the notification ID
            const notificationElement = $(this);

            // Send AJAX request to update the notification as read
            $.ajax({
                url: 'mark_as_read.php', // PHP script to update the notification as read
                method: 'POST',
                data: { notification_id: notificationId },
                success: function () {
                    // Change the appearance of the notification (bland color for read)
                    notificationElement.addClass('bg-light text-muted');
                },
                error: function () {
                    alert('Error marking notification as read.');
                }
            });
        });
    });
</script>






