<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Listing Setup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body {
      background-color: #faf9f5;
      color: #333;
    }
    header, footer {
      background-color: #fff;
    }
    h4, h5 {
      color: #00524e;
      font-weight: 600;
    }
    .btn-primary {
      background-color: #00524e;
      border-color: #00524e;
    }
    .btn-primary:hover {
      background-color: #00433f;
      border-color: #00433f;
    }
    .btn-success {
      background-color: #00524e;
      border-color: #00524e;
    }
    .btn-success:hover {
      background-color: #00433f;
      border-color: #00433f;
    }
    main .container {
      max-width: 1000px;
    }
    .card {
      border: none;
      border-radius: 12px;
    }
    
    /* Custom loading modal styles */
    .loading-modal .modal-content {
      background: transparent;
      border: none;
      box-shadow: none;
    }
    .loading-modal .modal-body {
      text-align: center;
      color: #00524e;
      background-color: #fff;
      border-radius: 15px;
    }
    .loading-spinner {
      width: 3rem;
      height: 3rem;
      border: 0.3em solid rgba(0, 82, 78, 0.2);
      border-left-color: #00524e;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* Custom toast container positioning */
    .swal2-container {
      padding: 1.5em 1.5em 0 1.5em;
    }
    .swal2-toast {
      margin-bottom: 1em;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Row 1: Header -->
  <header class="border-bottom py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
      <!-- Logo -->
      <div>
        <img src="images/full-logo.png" alt="Logo" height="40">
      </div>
      <!-- Future Button (commented out) -->
      <!-- <button class="btn btn-primary">Save & Exit</button> -->
    </div>
  </header>

  <!-- Row 2: Main Content -->
  <main class="flex-grow-1 d-flex align-items-center py-5">
    <div class="container">
      <div class="row justify-content-center align-items-center g-5">
        <!-- Left Column -->
        <div class="col-lg-6" style="text-align: left; margin-left: -200px; margin-right: 100px;">
          <h4 class="mb-4" style="font-weight: 700; text-transform: uppercase; font-size: 2.5rem; width: 800px;">It's easy to get started on Furnished Unfinished</h4>
          
          <div class="mb-4">
            <h5 style="font-size: 2rem;">1. Tell us about your place</h5>
            <p class="text-muted" style="font-size: 1.2rem;">
              Share some basic info, like where it is and how many guests can stay.
            </p>
          </div>
          
          <div class="mb-4">
            <h5 style="font-size: 2rem;">2. Make it stand out</h5>
            <p class="text-muted" style="font-size: 1.2rem;">
              Add 5 or more photos plus a title and description—we'll help you out.
            </p>
          </div>
          
          <div class="mb-4">
            <h5 style="font-size: 2rem;">3. Finish up and publish</h5>
            <p class="text-muted" style="font-size: 1.2rem;">
              Choose a starting price, verify a few details, then publish your listing.
            </p>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6" style="transform: translateX(100px);">
          <div class="card shadow-sm p-4">
            <div class="card-body">
              <h5 class="card-title mb-3 text-center text-dark">Register with Email</h5>
              
              <!-- Step 1: Enter Email -->
              <form id="emailForm">
                <div class="mb-3">
                  <label for="email" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
              </form>

              <!-- Step 2: Enter OTP (hidden initially) -->
              <form id="otpForm" class="mt-3 d-none">
                <div class="mb-3">
                  <label for="otp" class="form-label">Enter OTP</label>
                  <input type="text" class="form-control" id="otp" placeholder="Enter OTP" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Verify & Continue</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Row 3: Footer -->
  <footer class="py-3 border-top bg-white">
    <div class="container text-center">
      <small class="text-muted">&copy; 2025 Furnished Unfinished. All rights reserved.</small>
    </div>
  </footer>

  <!-- Loading Modal -->
  <div class="modal loading-modal" tabindex="-1" id="loadingModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loading-spinner"></div>
          <p>Sending OTP...</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  
<script>const emailForm = document.getElementById("emailForm");
const otpForm = document.getElementById("otpForm");
const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

// Function to show toast notification
function showToast(icon, title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'colored-toast'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    Toast.fire({
        icon: icon,
        title: title
    });
}

emailForm.addEventListener("submit", function(e) {
    e.preventDefault();
    let email = document.getElementById("email").value;
    
    // Show loading modal
    loadingModal.show();
    
    fetch("functions/send_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "email=" + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {
        // Hide loading modal
        loadingModal.hide();
        
        if (data.status === "redirect") {
            // Immediately redirect without showing any message
            window.location.href = data.redirect;
        } else if (data.status === "success") {
            showToast('success', data.message);
            emailForm.classList.add("d-none");
            otpForm.classList.remove("d-none");
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        // Hide loading modal
        loadingModal.hide();
        showToast('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    });
});

otpForm.addEventListener("submit", function(e) {
    e.preventDefault();
    let otp = document.getElementById("otp").value;
    let email = document.getElementById("email").value;
    
    // Show loading modal
    loadingModal.show();

    fetch("functions/verify_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "otp=" + encodeURIComponent(otp) + "&email=" + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {
        // Hide loading modal
        loadingModal.hide();
        
        console.log("OTP Response:", data); // Debug log
        
        if (data.status === "success") {
            showToast('success', data.message);
            // Use setTimeout to ensure toast is visible before redirect
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    console.error("No redirect URL provided");
                    showToast('error', 'Redirect URL missing');
                }
            }, 1500);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        // Hide loading modal
        loadingModal.hide();
        showToast('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    });
});</script>
</body>
</html>