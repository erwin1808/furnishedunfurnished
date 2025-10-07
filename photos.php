<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
    die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['property_photos'])) {

    $uploadDir = 'uploads/'; // make sure this folder exists and is writable
    $files = $_FILES['property_photos'];

    // Loop through each uploaded file
    for ($i = 0; $i < count($files['name']); $i++) {
        $tmpName = $files['tmp_name'][$i];
        $fileName = basename($files['name'][$i]);
        $targetFile = $uploadDir . time() . '_' . $fileName; // unique filename

        if (move_uploaded_file($tmpName, $targetFile)) {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO property_images (property_code, image_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $propertyCode, $targetFile);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Photos uploaded successfully!',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            setTimeout(function() {
                window.location.href = 'photos.php?an=" . urlencode($accountNumber) . "&pc=" . urlencode($propertyCode) . "';
            }, 1600);
        });
    </script>";
}

// Fetch existing images for preview
$stmt = $conn->prepare("SELECT id, image_path FROM property_images WHERE property_code = ?");
$stmt->bind_param("s", $propertyCode);
$stmt->execute();
$result = $stmt->get_result();
$existingImages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upload Property Photos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
body { background-color: #faf9f5; font-family: 'Inter', sans-serif; }
h1 { font-weight: 600; color: #00524e; margin-top: 50px; }
#preview-container {
    display: grid;
    grid-template-columns: 1fr 1fr; /* 2 columns for regular images */
    gap: 10px;
    margin-top: 20px;
}
.preview-image {
    position: relative;
    border: 2px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
}
.preview-image.cover {
    grid-column: span 2; /* cover spans both columns */
    height: 250px;
}
.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.preview-image .delete-btn {
    position: absolute;
    top: 2px; right: 2px;
    background: rgba(255,0,0,0.7);
    border: none;
    color: #fff;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
}
</style>
</head>
<body>

<header class="border-bottom py-3 shadow-sm bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>

<main class="flex-grow-1 container text-center" style="margin-bottom: 120px;">
<h1>Upload Property Photos</h1>

<form method="POST" enctype="multipart/form-data" id="photoForm">
    <input type="file" id="property_photos" name="property_photos[]" multiple accept="image/*" class="form-control mb-3">
<div id="preview-container">
    <?php foreach ($existingImages as $i => $img): ?>
        <div class="preview-image <?= $i === 0 ? 'cover' : '' ?>" data-id="<?= $img['id'] ?>">
            <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Property Image">
            <button type="button" class="delete-btn" onclick="deleteExistingImage(<?= $img['id'] ?>)">×</button>
        </div>
    <?php endforeach; ?>
</div>
</form>
</main>

<footer class="fixed-bottom border-top bg-white">
  <div class="progress" style="height: 6px;">
    <div class="progress-bar bg-secondary" style="width: 60%;"></div>
  </div>
  <div class="d-flex justify-content-between align-items-center px-4 py-3">
    <button type="button" class="btn btn-link text-muted" onclick="history.back()">← Back</button>
    <button type="submit" form="photoForm" class="btn btn-primary px-4" style="background-color:#00524e; border-color:#00524e;">
        Save Photos
    </button>
  </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const input = document.getElementById('property_photos');
const previewContainer = document.getElementById('preview-container');

input.addEventListener('change', () => {
    const files = input.files;
    previewContainer.innerHTML = ''; // clear previous previews

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.classList.add('preview-image');
            if(index === 0) div.classList.add('cover'); // first file is cover
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="delete-btn" onclick="deletePreview(this)">×</button>
            `;
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

function deletePreview(btn) {
    btn.parentElement.remove();
}
function deleteExistingImage(id) {
    if(confirm("Delete this image?")) {
        fetch('delete_property_image.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.querySelector('.preview-image[data-id=\"'+id+'\"]').remove();
            }
        });
    }
}
</script>

</body>
</html>
