<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
    die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// Handle AJAX file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    if (isset($_FILES['property_photos']) && !empty($_FILES['property_photos']['name'][0])) {
        $files = $_FILES['property_photos'];
        $uploadSuccess = false;

        // Get current max image_order
        $stmt = $conn->prepare("SELECT MAX(image_order) AS max_order FROM property_images WHERE property_code = ?");
        $stmt->bind_param("s", $propertyCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $maxOrder = $row['max_order'] ?? 0;
        $stmt->close();

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            $tmpName = $files['tmp_name'][$i];
            $fileName = basename($files['name'][$i]);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $targetFile = $uploadDir . $uniqueFileName;

            if (getimagesize($tmpName) === false) continue;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $maxOrder++;
                $stmt = $conn->prepare("INSERT INTO property_images (property_code, image_path, image_order) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $propertyCode, $targetFile, $maxOrder);
                if ($stmt->execute()) $uploadSuccess = true;
                else unlink($targetFile);
                $stmt->close();
            }
        }

        // Return JSON for AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => $uploadSuccess]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }
}

// Fetch existing images
$stmt = $conn->prepare("SELECT id, image_path, image_order FROM property_images WHERE property_code = ? ORDER BY image_order ASC");
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
h1 { font-weight: 600; color: #00524e; margin-top: 50px; text-align:center; }
#main-container { max-width: 800px; margin: 0 auto; padding: 20px; }
.cover-container, #grid-container { min-height: 200px; background-color: #fff; border: 2px dashed #ccc; border-radius: 12px; padding: 10px; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 10px; }
.cover-image, .grid-image { position: relative; border-radius: 8px; overflow: hidden; }
.cover-image { width: 100%; max-width: 500px; height: 250px; }
.grid-image { width: calc(50% - 10px); height: 150px; border: 2px solid #e9ecef; }
.cover-image img, .grid-image img { width: 100%; height: 100%; object-fit: cover; }
.delete-btn { position: absolute; top: 5px; right: 5px; background: rgba(220,53,69,0.9); border: none; color: #fff; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; z-index: 3; }
.empty-state { text-align:center; color:#6c757d; padding:20px; font-style:italic; width:100%; }
</style>
</head>
<body>

<header class="border-bottom py-3 shadow-sm bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>

<main class="flex-grow-1" style="margin-bottom: 120px;">
    <div id="main-container">
        <h1>Upload Property Photos</h1>

        <input type="file" id="property_photos" multiple accept="image/*" class="form-control mb-3">
        <div class="form-text mb-4">Select multiple photos. First photo becomes cover if no existing cover.</div>

        <div id="cover-section">
            <span class="cover-label">Cover Photo</span>
            <div class="cover-container" id="cover-dropzone">
                <?php if (!empty($existingImages)): ?>
                    <div class="cover-image" data-id="db_<?= $existingImages[0]['id'] ?>">
                        <img src="<?= htmlspecialchars($existingImages[0]['image_path']) ?>" alt="Cover Image">
                        <button type="button" class="delete-btn" onclick="deleteExistingImage('<?= $existingImages[0]['id'] ?>')">×</button>
                    </div>
                <?php else: ?>
                    <div class="empty-state">No cover photo selected</div>
                <?php endif; ?>
            </div>
        </div>

        <div id="grid-section">
            <span class="grid-label">Additional Photos</span>
            <div id="grid-container">
                <?php if (!empty($existingImages) && count($existingImages) > 1): ?>
                    <?php foreach(array_slice($existingImages,1) as $img): ?>
                        <div class="grid-image" data-id="db_<?= $img['id'] ?>">
                            <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Property Image">
                            <button type="button" class="delete-btn" onclick="deleteExistingImage('<?= $img['id'] ?>')">×</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No additional photos</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.16.0/Sortable.min.js"></script>
<script>
const input = document.getElementById('property_photos');
const coverDropzone = document.getElementById('cover-dropzone');
const gridContainer = document.getElementById('grid-container');

// Upload immediately on selection
input.addEventListener('change', () => {
    if(input.files.length === 0) return;

    const formData = new FormData();
    Array.from(input.files).forEach(file => formData.append('property_photos[]', file));

    fetch(`?an=<?= $accountNumber ?>&pc=<?= $propertyCode ?>`, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Photos uploaded!', showConfirmButton:false, timer:1500 })
            .then(() => location.reload());
        } else {
            Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Upload failed!', showConfirmButton:false, timer:1500 });
        }
    })
    .catch(err => console.error(err));
});

// Delete existing images
function deleteExistingImage(id){
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the image!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if(result.isConfirmed){
            fetch('functions/delete_property_image.php?id=' + id)
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    const el = document.querySelector(`[data-id="db_${id}"]`);
                    if(el) el.remove();

                    // If deleted image was cover, promote first grid image
                    if(el && el.classList.contains('cover-image')){
                        const firstGrid = document.querySelector('#grid-container .grid-image');
                        if(firstGrid){
                            const gridId = firstGrid.getAttribute('data-id');
                            const imgSrc = firstGrid.querySelector('img').src;

                            // Remove from grid
                            firstGrid.remove();

                            // Set as cover
                            const coverDiv = document.createElement('div');
                            coverDiv.classList.add('cover-image');
                            coverDiv.setAttribute('data-id', gridId);
                            coverDiv.innerHTML = `
                                <img src="${imgSrc}" alt="Cover Image">
                                <button type="button" class="delete-btn" onclick="deleteExistingImage('${gridId.replace('db_','')}')">×</button>
                            `;
                            const coverDropzone = document.getElementById('cover-dropzone');
                            coverDropzone.innerHTML = '';
                            coverDropzone.appendChild(coverDiv);
                        } else {
                            // No grid images left, show empty state
                            document.getElementById('cover-dropzone').innerHTML = '<div class="empty-state">No cover photo selected</div>';
                        }
                    }

                    // Show toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Image deleted successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // If grid empty, show empty state
                    const gridContainer = document.getElementById('grid-container');
                    if(gridContainer.children.length === 0){
                        gridContainer.innerHTML = '<div class="empty-state">No additional photos</div>';
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Failed to delete image!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}

// Initialize Sortable for grid
new Sortable(gridContainer, { animation:150, ghostClass:'sortable-ghost', chosenClass:'sortable-chosen' });
</script>

</body>
</html>
