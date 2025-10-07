<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
  die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_access_type'])) {
  $placeAccessType = trim($_POST['place_access_type']);

  if (!empty($placeAccessType)) {
    $stmt = $conn->prepare("UPDATE property 
                            SET place_access_type = ? 
                            WHERE account_number = ? AND property_code = ?");
    $stmt->bind_param("sss", $placeAccessType, $accountNumber, $propertyCode);

    if ($stmt->execute()) {
      $message = "Place type saved successfully!";
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '$message',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
          });
          setTimeout(function() {
            window.location.href = 'location.php?an=" . urlencode($accountNumber) . "&pc=" . urlencode($propertyCode) . "';
          }, 1600);
        });
      </script>";
    } else {
      echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save place type. Please try again.'
        });
      </script>";
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>What Type of Place?</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body {
      background-color: #faf9f5;
      font-family: 'Inter', sans-serif;
      color: #333;
    }
    h4 {
      color: #00524e;
      font-weight: 600;
      font-size: 2rem;
    }
    .place-card {
      cursor: pointer;
      border: 2px solid #ccc;
      transition: all 0.2s ease-in-out;
      background-color: #fff;
      border-radius: 1rem;
      padding: 1rem 1rem;
    }
    .place-card:hover {
      border-color: #6c757d;
      transform: translateY(-2px);
    }
    .place-card.selected {
      border-color: #000;
      box-shadow: 0 0 0 4px rgba(0, 82, 78, 0.15);
    }
    .icon-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .icon-wrapper i {
      font-size: 3rem;
      color: #00524e;
    }
    footer {
      background-color: #fff;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="border-bottom py-3 shadow-sm bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>

<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container my-5" style="max-width: 700px;">
    <h4 class="text-center mb-4" style="
    text-align: center; 
    font-weight: 600; 
    font-size: 2rem; 
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  ">What type of place will guests have?</h4>

    <form method="POST" id="placeForm">
      <input type="hidden" name="place_access_type" id="placeTypeInput">

      <div class="d-flex flex-column gap-4">
        <!-- Entire place -->
        <div class="card place-card" data-value="Entire place">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="text-start">
              <h5 class="fw-bold mb-1">An entire place</h5>
              <p class="text-muted small mb-0">
                Guests have the whole place to themselves.
              </p>
            </div>
            <div class="icon-wrapper ms-3">
              <i class="bi bi-house-door"></i>
            </div>
          </div>
        </div>

        <!-- Private room -->
        <div class="card place-card" data-value="Private room">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="text-start">
              <h5 class="fw-bold mb-1">A room</h5>
              <p class="text-muted small mb-0">
                Guests have their own room in a home, plus access to shared spaces.
              </p>
            </div>
            <div class="icon-wrapper ms-3">
              <i class="bi bi-door-open"></i>
            </div>
          </div>
        </div>

        <!-- Shared room -->
        <div class="card place-card" data-value="Shared room">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="text-start">
              <h5 class="fw-bold mb-1">A shared room in a hostel</h5>
              <p class="text-muted small mb-0">
                Guests sleep in a shared room in a professionally managed hostel with staff onsite 24/7.
              </p>
            </div>
            <div class="icon-wrapper ms-3">
              <i class="bi bi-people"></i>
            </div>
          </div>
        </div>
      </div>

      <footer class="fixed-bottom border-top">
        <div class="progress" style="height: 6px;">
          <div class="progress-bar bg-secondary" style="width: 30%;"></div>
        </div>
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
          <button type="button" class="btn btn-link text-muted" onclick="history.back()">← Back</button>
          <button type="submit" class="btn btn-primary px-4" style="background-color:#00524e; border-color:#00524e;">Save</button>
        </div>
      </footer>
    </form>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
document.querySelectorAll('.place-card').forEach(card => {
  card.addEventListener('click', () => {
    document.querySelectorAll('.place-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById("placeTypeInput").value = card.getAttribute("data-value");
  });
});

document.getElementById("placeForm").addEventListener("submit", function(e) {
  if (!document.getElementById("placeTypeInput").value) {
    e.preventDefault();
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'warning',
      title: 'Please select a type before saving!',
      showConfirmButton: false,
      timer: 2500
    });
  }
});
</script>

</body>
</html>
