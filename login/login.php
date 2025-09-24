<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
header('Surrogate-Control: no-store');

// Check if request is AJAX
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $user = authenticateUser($email, $password);

    if ($user) {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['roles'] = $user['roles'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['account_number'] = $user['account_number'];

        $redirect = ($user['roles'] === 'admin') ? '../admin/admindb.php' : '../home/landing.php';

        if ($is_ajax) {
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit;
        } else {
            header("Location: $redirect");
            exit;
        }
    } else {
        if ($is_ajax) {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Streamlinedstays</title>
    <?php include "../includes/icon.php"; ?>

    <!-- SB Admin 2 CSS -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-primary">

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-5 col-lg-6 col-md-8">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">

                    <!-- Login Heading -->
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Streamlinedstays Login</h1>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" class="user" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Login
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a class="small" href="../register/register.php">Create an Account</a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- SB Admin 2 JS -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- AJAX Login Script -->
<script>
document.getElementById('loginForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const isMobile = window.innerWidth <= 768;

  fetch('', {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      Swal.fire({
        toast: true,
        position: isMobile ? 'top' : 'bottom-end',
        icon: 'error',
        title: data.message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: 'error',
      title: 'Network error. Please try again.',
      toast: true,
      position: isMobile ? 'top' : 'bottom-end',
      showConfirmButton: false,
      timer: 3000
    });
  });
});
</script>

</body>
</html>
