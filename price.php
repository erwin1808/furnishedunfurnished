<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
    die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// 🟢 Fetch current monthly rent to populate input
$currentRent = "";
$stmt = $conn->prepare("SELECT monthly_rent FROM property WHERE account_number = ? AND property_code = ?");
$stmt->bind_param("ss", $accountNumber, $propertyCode);
$stmt->execute();
$stmt->bind_result($monthly_rent);
if ($stmt->fetch()) {
    // 🔹 Format without decimals
    $currentRent = $monthly_rent ? number_format($monthly_rent, 0, '.', ',') : "";
}
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['monthly_price'])) {
    $monthlyRent = floatval(str_replace(',', '', $_POST['monthly_price']));

    if ($monthlyRent > 0) {
        $stmt = $conn->prepare("UPDATE property 
                                SET monthly_rent = ? 
                                WHERE account_number = ? AND property_code = ?");
        $stmt->bind_param("dss", $monthlyRent, $accountNumber, $propertyCode);

        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Listing uploaded!',
                        text: 'Complete other property details on your dashboard.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#00524e',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'landlord/dashboard.php';
                        }
                    });
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save monthly rent. Please try again.'
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
<title>Set Monthly Rent</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        text-align: left;
        margin-bottom: 1rem;
    }
    p {
        text-align: left;
        margin-bottom: 2rem;
        color: #555;
    }

    .container-price {
        max-width: 700px;
        margin: 0 auto;
    }
    .price-box {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: none;
        border-radius: 20px;
        padding: 20px;
        font-size: 3rem;
        font-weight: 500;
    }
    .price-box span {
        font-size: 5rem;
        margin-right: 8px;
        color: #555;
    }
    #monthlyPrice {
        font-size: 5rem;
        max-width: 300px;
        text-align: center;
        border: none;
        outline: none;
        background: transparent;
        font-weight: 600;
        color: #222;
    }
    #monthlyPrice:focus {
        box-shadow: none;
    }
    .form-label {
        font-size: 1.3rem;
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
  <div class="container container-price my-5">
    <h4>Now, set a monthly base rent</h4>
    <p>💡 Tip: $450 per month is a good starting point. You’ll set seasonal prices next.</p>

    <form method="POST" id="priceForm">
      <div class="mb-4 text-center">
        <div class="price-box mx-auto">
          <span>$</span>
<input type="text" name="monthly_price" id="monthlyPrice" 
       placeholder="450" 
       value="<?= htmlspecialchars($currentRent) ?>" required>

        </div>
        <label for="monthlyPrice" class="form-label mb-2">Monthly Rent (USD)</label>
      </div>

      <footer class="fixed-bottom border-top">
        <div class="progress" style="height: 6px;">
          <div class="progress-bar bg-secondary" style="width: 95%;"></div>
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
// Format input with commas dynamically
const priceInput = document.getElementById('monthlyPrice');

priceInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/,/g, '');
    if (!isNaN(value) && value !== '') {
        e.target.value = parseFloat(value).toLocaleString('en-US', {
            maximumFractionDigits: 2
        });
    } else {
        e.target.value = '';
    }
});

// Validate before submitting
document.getElementById('priceForm').addEventListener('submit', function(e) {
    let rawValue = priceInput.value.replace(/,/g, '');
    if (!rawValue.trim() || parseFloat(rawValue) <= 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Please enter a valid monthly rent before saving!',
            confirmButtonColor: '#00524e'
        });
    }
});
</script>

</body>
</html>
