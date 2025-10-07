<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
  die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// Fetch existing property data
$stmt = $conn->prepare("SELECT guests, bedrooms, beds, baths 
                        FROM property WHERE account_number = ? AND property_code = ?");
$stmt->bind_param("ss", $accountNumber, $propertyCode);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();
$stmt->close();

// Save basic info
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $guests = intval($_POST['guests']);
  $bedrooms = intval($_POST['bedrooms']);
  $beds = intval($_POST['beds']);
  $baths = intval($_POST['baths']);

  $stmt = $conn->prepare("UPDATE property 
                          SET guests = ?, bedrooms = ?, beds = ?, baths = ?
                          WHERE account_number = ? AND property_code = ?");
  $stmt->bind_param("iiiiss", $guests, $bedrooms, $beds, $baths, $accountNumber, $propertyCode);

  if ($stmt->execute()) {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Details Saved!',
          text: 'Basic details about your place have been saved successfully.',
          timer: 1500,
          showConfirmButton: false
        });
        setTimeout(() => {
          window.location.href = 'anemities.php?an=" . urlencode($accountNumber) . "&pc=" . urlencode($propertyCode) . "';
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
  <title>Basic Property Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
  body {
    background-color: #faf9f5;
    font-family: 'Inter', sans-serif;
  }
  h3 {
    color: #00524e;
    font-weight: 600;
  }
  p {
    color: #555;
  }
  .counter-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e0e0e0;
  }
  .counter-controls {
  display: flex;
  align-items: center;
  gap: 4px; /* reduced from 10px for tighter spacing */
}

.counter-btn {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  font-size: 18px;
  color: #333;
  text-align: center;
  line-height: 32px;
  cursor: pointer;
  transition: all 0.2s;
}

.counter-btn:hover {
  border-color: #00524e;
  color: #00524e;
}

.counter-value {
  font-size: 18px;
  width: 40px; /* fixed width for balance */
  text-align: center;
  font-weight: 500;
  background-color: transparent;
  border: none;
  outline: none;
  color: #333;
  padding: 0; /* removes inner gap */
  margin: 0; /* removes outer gap */
}

  .card-medium {
    background: transparent;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
  }
  footer {
    background-color: #fff;
  }
</style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="border-bottom py-3 bg-white shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>


<main class="flex-grow-1">
  <div class="container my-7" style="transform: translateY(200px);">
    <!-- Header Section -->
    <div class="text-center mb-4" >
      <h3 style="
    text-align: center; 
    font-weight: 600; 
    font-size: 2rem; 
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  ">Share some basics about your place</h3>
      <p>You'll add more details later, like bed types.</p>
    </div>

    <!-- Medium Card Form -->
    <form method="POST" id="basicInfoForm" class="card-medium">

      <div class="counter-row">
        <span>Guests</span>
        <div class="counter-controls">
          <button type="button" class="counter-btn" onclick="adjustValue('guests', -1)">−</button>
          <input type="text" id="guests" name="guests" class="counter-value" value="<?= htmlspecialchars($property['guests'] ?? 1) ?>" readonly>
          <button type="button" class="counter-btn" onclick="adjustValue('guests', 1)">+</button>
        </div>
      </div>

      <div class="counter-row">
        <span>Bedrooms</span>
        <div class="counter-controls">
          <button type="button" class="counter-btn" onclick="adjustValue('bedrooms', -1)">−</button>
          <input type="text" id="bedrooms" name="bedrooms" class="counter-value" value="<?= htmlspecialchars($property['bedrooms'] ?? 1) ?>" readonly>
          <button type="button" class="counter-btn" onclick="adjustValue('bedrooms', 1)">+</button>
        </div>
      </div>

      <div class="counter-row">
        <span>Beds</span>
        <div class="counter-controls">
          <button type="button" class="counter-btn" onclick="adjustValue('beds', -1)">−</button>
          <input type="text" id="beds" name="beds" class="counter-value" value="<?= htmlspecialchars($property['beds'] ?? 1) ?>" readonly>
          <button type="button" class="counter-btn" onclick="adjustValue('beds', 1)">+</button>
        </div>
      </div>

      <div class="counter-row">
        <span>Bathrooms</span>
        <div class="counter-controls">
          <button type="button" class="counter-btn" onclick="adjustValue('baths', -1)">−</button>
          <input type="text" id="baths" name="baths" class="counter-value" value="<?= htmlspecialchars($property['baths'] ?? 1) ?>" readonly>
          <button type="button" class="counter-btn" onclick="adjustValue('baths', 1)">+</button>
        </div>
      </div>

    </form>
  </div>

  <!-- Footer -->
  <footer class="fixed-bottom border-top">
    <div class="progress" style="height: 6px;">
      <div class="progress-bar bg-secondary" style="width: 40%;"></div>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white">
      <button type="button" class="btn btn-link text-muted" onclick="history.back()">← Back</button>
      <button type="submit" form="basicInfoForm" class="btn btn-primary px-4" style="background-color:#00524e; border-color:#00524e;">Save</button>
    </div>
  </footer>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function adjustValue(field, delta) {
  const input = document.getElementById(field);
  let value = parseInt(input.value) || 0;
  value = Math.max(0, value + delta);
  input.value = value;
}
</script>


</body>
</html>
