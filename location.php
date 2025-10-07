<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
  die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// Fetch existing property data
$stmt = $conn->prepare("SELECT street, barangay, city, province, postal_code, latitude, longitude 
                        FROM property WHERE account_number = ? AND property_code = ?");
$stmt->bind_param("ss", $accountNumber, $propertyCode);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();
$stmt->close();

// Save address + map coordinates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $street = trim($_POST['street']);
  $barangay = trim($_POST['barangay']);
  $city = trim($_POST['city']);
  $province = trim($_POST['province']);
  $postal_code = trim($_POST['postal_code']);
  $latitude = trim($_POST['latitude']);
  $longitude = trim($_POST['longitude']);

  $stmt = $conn->prepare("UPDATE property 
                          SET street = ?, barangay = ?, city = ?, province = ?, postal_code = ?, latitude = ?, longitude = ?
                          WHERE account_number = ? AND property_code = ?");
  $stmt->bind_param("ssssddsss", $street, $barangay, $city, $province, $postal_code, $latitude, $longitude, $accountNumber, $propertyCode);

  if ($stmt->execute()) {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Location Saved!',
          text: 'Your property address and map location have been saved successfully.',
          timer: 1500,
          showConfirmButton: false
        });
        setTimeout(() => {
          window.location.href = 'floor-plan.php?an=" . urlencode($accountNumber) . "&pc=" . urlencode($propertyCode) . "';
        }, 1600);
      });
    </script>";
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Property Location</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body {
      background-color: #faf9f5;
      font-family: 'Inter', sans-serif;
    }
    h4 { color: #00524e; font-weight: 600; }
    #map { height: 400px; border-radius: 1rem; border: 2px solid #ddd; }
    .form-control { border-radius: 0.75rem; padding: 0.75rem; }
    .step-card { background-color: #fff; border-radius: 1rem; box-shadow: 0 3px 10px rgba(0,0,0,0.08); }
    footer { background-color: #fff; }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="border-bottom py-3 bg-white shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>

<main class="flex-grow-1">
  <div class="container my-5">
    <h4 class="text-center mb-4"style="
    text-align: center; 
    font-weight: 600; 
    font-size: 2rem; 
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  ">Set Your Property Location</h4>

    <form method="POST" id="locationForm" class="step-card p-4">
      <div class="row g-4">
        <!-- Address Fields -->
        <div class="col-md-4">
          <h5 class="mb-3 text-muted">Address Details</h5>

          <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" class="form-control" name="street" placeholder="e.g., 221B Baker Street" required
                   value="<?= htmlspecialchars($property['street'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">District / Neighborhood</label>
            <input type="text" class="form-control" name="barangay" placeholder="e.g., Downtown District" required
                   value="<?= htmlspecialchars($property['barangay'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">City / Municipality</label>
            <input type="text" class="form-control" name="city" placeholder="e.g., New York City" required
                   value="<?= htmlspecialchars($property['city'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">State / Province / Region</label>
            <input type="text" class="form-control" name="province" placeholder="e.g., California" required
                   value="<?= htmlspecialchars($property['province'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" class="form-control" name="postal_code" placeholder="e.g., 10001"
                   value="<?= htmlspecialchars($property['postal_code'] ?? '') ?>">
          </div>
        </div>

        <!-- Map Section -->
        <div class="col-md-8">
          <h5 class="mb-3 text-muted">Pin Exact Location</h5>
          <p class="small text-secondary mb-3">Drag or click the map to mark your property’s exact spot.</p>
          <div id="map"></div>

          <input type="hidden" id="latitude" name="latitude" value="<?= htmlspecialchars($property['latitude'] ?? '') ?>">
          <input type="hidden" id="longitude" name="longitude" value="<?= htmlspecialchars($property['longitude'] ?? '') ?>">
        </div>
      </div>

      <!-- Standard Footer -->
      <footer class="fixed-bottom border-top">
        <div class="progress" style="height: 6px;">
          <div class="progress-bar bg-secondary" style="width: 30%;"></div>
        </div>
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white">
          <button type="button" class="btn btn-link text-muted" onclick="history.back()">← Back</button>
          <button type="submit" class="btn btn-primary px-4" style="background-color:#00524e; border-color:#00524e;">Save</button>
        </div>
      </footer>
    </form>
  </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  let lat = <?= $property['latitude'] ?? 14.5995 ?>;
  let lng = <?= $property['longitude'] ?? 120.9842 ?>;

  const map = L.map('map').setView([lat, lng], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

  function updateCoords(pos) {
    document.getElementById('latitude').value = pos.lat.toFixed(8);
    document.getElementById('longitude').value = pos.lng.toFixed(8);
  }

  marker.on('dragend', () => updateCoords(marker.getLatLng()));
  map.on('click', e => {
    marker.setLatLng(e.latlng);
    updateCoords(e.latlng);
  });
});
</script>

</body>
</html>
