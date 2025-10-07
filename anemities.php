<?php
include "includes/db.php";
session_start();

if (!isset($_GET['an']) || !isset($_GET['pc'])) {
    die("Missing parameters.");
}

$accountNumber = $_GET['an'];
$propertyCode = $_GET['pc'];

// Fetch existing amenities
$stmt = $conn->prepare("SELECT amenity_name FROM property_amenities WHERE property_code = ? AND account_number = ?");
$stmt->bind_param("ss", $propertyCode, $accountNumber);
$stmt->execute();
$result = $stmt->get_result();
$existingAmenities = [];
while ($row = $result->fetch_assoc()) {
    $existingAmenities[] = $row['amenity_name'];
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedAmenities = $_POST['amenities'] ?? [];

    // Remove existing amenities
    $stmt = $conn->prepare("DELETE FROM property_amenities WHERE property_code = ? AND account_number = ?");
    $stmt->bind_param("ss", $propertyCode, $accountNumber);
    $stmt->execute();
    $stmt->close();

    // Insert selected amenities
    if (!empty($selectedAmenities)) {
        $stmt = $conn->prepare("INSERT INTO property_amenities (property_code, account_number, amenity_name) VALUES (?, ?, ?)");
        foreach ($selectedAmenities as $amenity) {
            $stmt->bind_param("sss", $propertyCode, $accountNumber, $amenity);
            $stmt->execute();
        }
        $stmt->close();
    }

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Amenities Saved!',
                text: 'Your property amenities have been updated.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    </script>";
}

// Amenity categories with Bootstrap Icons
$amenityCategories = [
    "Guest Favorites" => [
        ["name" => "Wifi", "icon" => "bi-wifi"],
        ["name" => "TV", "icon" => "bi-tv"],
        ["name" => "Kitchen", "icon" => "bi-suit-heart"],
        ["name" => "Washer", "icon" => "bi-droplet"],
        ["name" => "Free parking on premises", "icon" => "bi-parking"],
        ["name" => "Paid parking on premises", "icon" => "bi-cash-stack"],
        ["name" => "Air conditioning", "icon" => "bi-snow"],
        ["name" => "Dedicated workspace", "icon" => "bi-laptop"]
    ],
    "Standout Amenities" => [
        ["name" => "Pool", "icon" => "bi-water"],
        ["name" => "Hot tub", "icon" => "bi-droplet-half"],
        ["name" => "Patio", "icon" => "bi-house-door"],
        ["name" => "BBQ grill", "icon" => "bi-fire"],
        ["name" => "Outdoor dining area", "icon" => "bi-cup"],
        ["name" => "Fire pit", "icon" => "bi-flame"],
        ["name" => "Pool table", "icon" => "bi-dice-6"],
        ["name" => "Indoor fireplace", "icon" => "bi-house-heart"],
        ["name" => "Piano", "icon" => "bi-music-note"],
        ["name" => "Exercise equipment", "icon" => "bi-bicycle"],
        ["name" => "Lake access", "icon" => "bi-water"],
        ["name" => "Beach access", "icon" => "bi-sun"],
        ["name" => "Ski-in/Ski-out", "icon" => "bi-snow2"],
        ["name" => "Outdoor shower", "icon" => "bi-shower"]
    ],
    "Safety Items" => [
        ["name" => "Smoke alarm", "icon" => "bi-exclamation-triangle"],
        ["name" => "First aid kit", "icon" => "bi-heart-pulse"],
        ["name" => "Fire extinguisher", "icon" => "bi-fire"],
        ["name" => "Carbon monoxide alarm", "icon" => "bi-thermometer-half"]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Property Amenities</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
body { background-color: #faf9f5; font-family: 'Inter', sans-serif; }
h1 { font-weight: 600; color: #00524e; margin-top: 100px; }
p.subtext { color: #555; margin-bottom: 40px; }.amenity-grid {
    display: grid;
    grid-template-columns: repeat(3, 200px); /* fixed 3 columns of 200px */
    gap: 15px;
    justify-content: center; /* center the grid horizontally */
}

.amenity-card {
    border: 2px solid #ccc;
    border-radius: 10px;
    padding: 12px 8px;
    display: flex;
    flex-direction: column; /* icon above text */
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background-color: transparent;
    transition: all 0.2s;
    width: 200px;   /* fixed width */
    height: 125px;  /* fixed height */
    text-align: center;
}
.category-title {
    text-align: center;
    font-weight: 600;
    color: #00524e;
    margin-bottom: 10px;
}

.amenity-card.selected {
    border-color: #00524e;
    background-color: #e6f2f2;
}

.icon-wrapper {
    font-size: 1.8rem;
    margin-bottom: 6px; /* space between icon and text */
}

footer { background-color: #fff; }
</style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="border-bottom py-3 shadow-sm bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <img src="images/full-logo.png" alt="Logo" height="40">
  </div>
</header>

<main class="flex-grow-1 container text-center" style="margin-bottom: 100px;">

<h1>Share what makes your place special</h1>
<p class="subtext">Select all amenities your property offers.</p>

<form method="POST" id="amenitiesForm">
    <?php foreach ($amenityCategories as $category => $amenities): ?>
      <h4 class="category-title mt-4 mb-3"><?= htmlspecialchars($category) ?></h4>

        <div class="amenity-grid mb-3">
        <?php foreach ($amenities as $amenity): 
            $isSelected = in_array($amenity['name'], $existingAmenities);
        ?>
            <div class="amenity-card <?= $isSelected ? 'selected' : '' ?>" data-name="<?= htmlspecialchars($amenity['name']) ?>">
                <div class="icon-wrapper">
                    <i class="bi <?= $amenity['icon'] ?>"></i>
                </div>
                <span><?= htmlspecialchars($amenity['name']) ?></span>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</form>

</main>

<footer class="fixed-bottom border-top">
    <div class="progress" style="height: 6px;">
      <div class="progress-bar bg-secondary" style="width: 60%;"></div>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white">
      <button type="button" class="btn btn-link text-muted" onclick="history.back()">← Back</button>
      <button type="submit" form="amenitiesForm" class="btn btn-primary px-4" style="background-color:#00524e; border-color:#00524e;">Save</button>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const cards = document.querySelectorAll('.amenity-card');
const form = document.getElementById('amenitiesForm');

cards.forEach(card => {
    card.addEventListener('click', () => {
        card.classList.toggle('selected');
    });
});

form.addEventListener('submit', function(e) {
    form.querySelectorAll('input[name="amenities[]"]').forEach(i => i.remove());
    cards.forEach(card => {
        if (card.classList.contains('selected')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'amenities[]';
            input.value = card.getAttribute('data-name');
            form.appendChild(input);
        }
    });
});
</script>

</body>
</html>
