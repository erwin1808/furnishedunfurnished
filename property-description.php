<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
    die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    $propertyDescription = trim($_POST['description']);

    if (!empty($propertyDescription)) {
        $stmt = $conn->prepare("UPDATE property 
                                SET description = ? 
                                WHERE account_number = ? AND property_code = ?");
        $stmt->bind_param("sss", $propertyDescription, $accountNumber, $propertyCode);

        if ($stmt->execute()) {
            $message = "Property description saved successfully!";
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
                        window.location.href = 'price.php?an=" . urlencode($accountNumber) . "&pc=" . urlencode($propertyCode) . "';
                    }, 1600);
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save property description. Please try again.'
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
<title>Property Description</title>
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
    .container-description {
        max-width: 600px;
        margin: 0 auto;
    }
    textarea.form-control {
        font-size: 1.1rem;
        text-align: left;
        height: 200px;
        border-radius: 15px;
        background-color: transparent;
        border: 1px solid #ccc;
        padding: 10px 15px;
        resize: none;
    }
    .char-counter {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: left;
        margin-top: 4px;
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
  <div class="container container-description my-5">
    <h4>Describe Your Property</h4>
    <p>Give potential guests a clear idea of your property. Keep it concise and informative.</p>

    <form method="POST" id="descriptionForm">
      <div class="mb-3 position-relative">
        <textarea name="description" id="descriptionInput" class="form-control" 
                  maxlength="300" required></textarea>
        <div id="charCounter" class="char-counter">0 / 300</div>
      </div>

      <footer class="fixed-bottom border-top">
        <div class="progress" style="height: 6px;">
          <div class="progress-bar bg-secondary" style="width: 90%;"></div>
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
const descriptionInput = document.getElementById('descriptionInput');
const charCounter = document.getElementById('charCounter');

descriptionInput.addEventListener('input', () => {
    const length = descriptionInput.value.length;
    charCounter.textContent = `${length} / 300`;
});

document.getElementById('descriptionForm').addEventListener('submit', function(e) {
    if (!descriptionInput.value.trim()) {
        e.preventDefault();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Please enter a property description before saving!',
            showConfirmButton: false,
            timer: 2500
        });
    }
});
</script>

</body>
</html>
