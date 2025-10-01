<?php
include "includes/db.php"; 
session_start();

if (!isset($_SESSION['account_number'])) {
    die("No account number found. Please login first.");
}
$accountNumber = $_SESSION['account_number']; // ✅ define it here

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_type'])) {
    $propertyType = $_POST['property_type'];

    // Function to generate unique property_code
    function generatePropertyCode($conn) {
        do {
            $code = strtoupper(bin2hex(random_bytes(3))); // 6-character alphanumeric
            $stmtCheck = $conn->prepare("SELECT intake_id  FROM property WHERE property_code = ?");
            $stmtCheck->bind_param("s", $code);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            $exists = $stmtCheck->num_rows > 0;
            $stmtCheck->close();
        } while ($exists);
        return $code;
    }

    // Optional: If you have a property_code coming from the form (for edit), use it
$propertyCode = isset($_POST['property_code']) && !empty($_POST['property_code']) 
                ? $_POST['property_code'] 
                : generatePropertyCode($conn);

// Check if property_code exists
$stmtCheck = $conn->prepare("SELECT intake_id FROM property WHERE property_code = ?");
$stmtCheck->bind_param("s", $propertyCode);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    // ✅ Update existing property
    $stmtCheck->bind_result($propertyId);
    $stmtCheck->fetch();
    $stmtCheck->close();

    $stmtUpdate = $conn->prepare("UPDATE property SET property_type = ? WHERE property_code = ?");
    $stmtUpdate->bind_param("ss", $propertyType, $propertyCode);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    $message = 'Property type updated successfully!';
} else {
    $stmtCheck->close();

    // ✅ Insert new property
    $stmtInsert = $conn->prepare("INSERT INTO property (account_number, property_type, property_code) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("sss", $accountNumber, $propertyType, $propertyCode);
    $stmtInsert->execute();
    $stmtInsert->close();

    $message = 'Property saved successfully!';
}

// Show toast
echo "<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '$message',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>";

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Listing Setup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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
        <img src="../images/full-logo.png" alt="Logo" height="40">
      </div>
      <!-- Future Button (commented out) -->
      <!-- <button class="btn btn-primary">Save & Exit</button> -->
    </div>
  </header>

<style>
  .place-card {
    cursor: pointer;
    background-color: transparent; /* always transparent */
    transition: all 0.2s ease-in-out;
    border: 2px solid #ccc; /* default gray border */
  }

  .place-card:hover {
    border-color: #6c757d; /* charcoal on hover */
  }

  .place-card.selected {
    border-color: #000; /* solid black when selected */
  }
</style>

<div class="container my-5">

  <!-- Main Content -->
  <div class="row justify-content-center text-center">
    <div class="col-12 mb-4" >
      <h4 style="text-align: center; font-weight: 600; font-size: 2rem;">Which of these best describes your place?</h4>
    </div>

    <div class="col-md-10">
<form method="POST" id="propertyForm">
  <input type="hidden" name="property_type" id="propertyTypeInput">
<input type="hidden" name="property_code" id="propertyCodeInput" value="<?= $propertyCode ?? '' ?>">

  <div class="row g-4 justify-content-center">
    <!-- House -->
    <div class="col-md-3 col-sm-5 col-6">
      <div class="card place-card rounded-3 p-3" data-value="House">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="bi bi-house display-4 mb-3 text-dark"></i>
          <h6 class="mb-0">House</h6>
        </div>
      </div>
    </div>

    <!-- Apartment -->
    <div class="col-md-3 col-sm-5 col-6">
      <div class="card place-card rounded-3 p-3" data-value="Apartment">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="bi bi-building display-4 mb-3 text-dark"></i>
          <h6 class="mb-0">Apartment</h6>
        </div>
      </div>
    </div>

    <!-- Room -->
    <div class="col-md-3 col-sm-5 col-6">
      <div class="card place-card rounded-3 p-3" data-value="Room">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="bi bi-door-open display-4 mb-3 text-dark"></i>
          <h6 class="mb-0">Room</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ KEEP THIS FOOTER -->
  <footer class="fixed-bottom bg-white border-top">
    <div class="progress" style="height: 6px;">
      <div class="progress-bar bg-secondary" role="progressbar" 
           style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3">
      <button type="button" class="btn btn-link text-muted">← Back</button>
      <button type="submit" class="btn btn-primary px-4">Save</button>
    </div>
  </footer>
</form>


    </div>
  </div>
</div>




<script>
document.querySelectorAll('.place-card').forEach(card => {
  card.addEventListener('click', () => {
    document.querySelectorAll('.place-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById("propertyTypeInput").value = card.getAttribute("data-value");
  });
});

document.getElementById("propertyForm").addEventListener("submit", function(e) {
  if (!document.getElementById("propertyTypeInput").value) {
    e.preventDefault();
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'warning',
      title: 'Please select a property type before saving!',
      showConfirmButton: false,
      timer: 3000
    });
  }
});

</script>



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
   window.location.href = 'next_step.php'; // change to your next page
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