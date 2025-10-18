<?php
include "includes/db.php";
require_once 'includes/init.php';
// Get location from URL parameter
$location = isset($_GET['location']) ? urldecode($_GET['location']) : '';

// Prepare SQL query to filter by city or street
$sql = "SELECT * FROM property WHERE city LIKE ? OR street LIKE ?";
$stmt = $conn->prepare($sql);
$searchLocation = "%" . $location . "%";
$stmt->bind_param("ss", $searchLocation, $searchLocation);
$stmt->execute();
$result = $stmt->get_result();

$properties = [];
$propertyCount = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Fetch images for this property
        $propertyCode = $row['property_code'];
        $imageSql = "SELECT * FROM property_images WHERE property_code = ? ORDER BY image_order ASC";
        $imageStmt = $conn->prepare($imageSql);
        $imageStmt->bind_param("s", $propertyCode);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();
        
        $images = [];
        while($imageRow = $imageResult->fetch_assoc()) {
            $images[] = $imageRow;
        }
        
        $row['images'] = $images;
        $properties[] = $row;
        $propertyCount++;
        
        $imageStmt->close();
    }
}

$stmt->close();
$conn->close();

// Function to get coordinates for a city (simple geocoding)
function getCityCoordinates($city) {
    $cityCoordinates = [
        'new york' => [40.7128, -74.0060],
        'los angeles' => [34.0522, -118.2437],
        'chicago' => [41.8781, -87.6298],
        'houston' => [29.7604, -95.3698],
        'phoenix' => [33.4484, -112.0740],
        'philadelphia' => [39.9526, -75.1652],
        'san antonio' => [29.4241, -98.4936],
        'san diego' => [32.7157, -117.1611],
        'dallas' => [32.7767, -96.7970],
        'san jose' => [37.3382, -121.8863],
        'austin' => [30.2672, -97.7431],
        'jacksonville' => [30.3322, -81.6557],
        'fort worth' => [32.7555, -97.3308],
        'columbus' => [39.9612, -82.9988],
        'charlotte' => [35.2271, -80.8431],
        'san francisco' => [37.7749, -122.4194],
        'indianapolis' => [39.7684, -86.1581],
        'seattle' => [47.6062, -122.3321],
        'denver' => [39.7392, -104.9903],
        'washington' => [38.9072, -77.0369],
        'boston' => [42.3601, -71.0589],
        'nashville' => [36.1627, -86.7816],
        'baltimore' => [39.2904, -76.6122],
        'portland' => [45.5152, -122.6784],
        'las vegas' => [36.1699, -115.1398],
        'milwaukee' => [43.0389, -87.9065],
        'albuquerque' => [35.0844, -106.6504],
        'tucson' => [32.2226, -110.9747],
        'fresno' => [36.7378, -119.7871],
        'sacramento' => [38.5816, -121.4944]
    ];
    
    $cityLower = strtolower(trim($city));
    return isset($cityCoordinates[$cityLower]) ? $cityCoordinates[$cityLower] : null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furnished Unfurnished - Property Search</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        /* Your existing CSS styles remain the same */
        :root {
            --primary: #1873ba;
            --primary-dark: #115283ff;
            --secondary: #0B47A8;
            --secondary-dark: #0B47A8;
            --dark: #111827;
            --darker: #0d1321;
            --light: #faf9f5;
            --gray: #6b7280;
            --light-gray: #e5e7eb;
            --success: #9ccc65;
        }
        
        * {
            transition: all 0.3s ease;
        }
        
        .leaflet-tile-container img {
            filter: grayscale(100%) contrast(110%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @font-face {
            font-family: "Commuters Sans";
            src: url("assets/fonts/Demo_Fonts/Fontspring-DEMO-commuterssans-regular.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
        }
        
        body {
            font-family: "Commuters Sans", sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
            scroll-behavior: smooth;
        }
        
        a {
            text-decoration: none;
            color: var(--primary);
        }
        
        ul {
            list-style: none;
        }
        
        .content-container {
            max-width: 1400px;
            margin: 200px auto 0;
            padding: 0 30px;
        }
        
        .btn-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: var(--accent);
            color: var(--dark);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .section-title {
            font-size: 2.6rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            color: #1b75bb;
            font-weight: 800;
            text-align: center;
            text-transform: capitalize;
            font-family: 'Commuters Sans', sans-serif;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--dark);
            max-width: 1200px;
            margin: -30px auto 80px;
            text-align: center;
            line-height: 1.7;
            font-family: 'Montserrat', sans-serif;
        }

        /* Property Search Page Styles */
        .search-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .search-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        
        .search-field {
            flex: 1;
            min-width: 180px;
        }
        
        .search-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #00524e;
            font-size: 0.9rem;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #00524e;
            box-shadow: 0 0 0 3px rgba(36, 48, 101, 0.1);
        }
        
        .search-btn {
            background-color: #00524e;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background-color: #1a2450;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 48, 101, 0.3);
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .results-count {
            font-size: 1.2rem;
            font-weight: 600;
            color: #00524e;
        }
        
        .save-search {
            background-color: transparent;
            color: #00524e;
            border: 1px solid #00524e;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .save-search:hover {
            background-color: #00524e;
            color: white;
        }
        
        .property-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .property-image-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        
        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .property-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #00524e;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .property-details {
            padding: 15px;
        }
        
        .property-type {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .property-type i {
            margin-right: 5px;
            color: #00524e;
        }
        
        .property-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .property-location {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .property-location i {
            margin-right: 5px;
            color: #00524e;
        }
        
        .property-status {
            display: inline-block;
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        
        .property-features {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .feature i {
            color: #00524e;
        }
        
        .property-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .amenity {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: #666;
        }
        
        .amenity i {
            color: #00524e;
        }
        
        .map-container {
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        #map {
            height: 600px;
            width: 100%;
        }
        
        .fullscreen-toggle {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            cursor: pointer;
            display: none;
        }
        
        .fullscreen-toggle:hover {
            background-color: #f5f5f5;
        }
        
        .map-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }
        
        .map-fullscreen #map {
            height: 100%;
        }
        
        .close-fullscreen {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            cursor: pointer;
        }
        
        .image-slider {
            position: relative;
            height: 100%;
        }
        
        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }
        
        .prev-btn {
            left: 10px;
        }
        
        .next-btn {
            right: 10px;
        }
        
        .image-counter {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-results i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        @media (max-width: 991px) {
            .map-container {
                margin-top: 30px;
                height: 400px;
            }
        }
        /* Make the main container flex and set heights */
.container-fluid.py-4 {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 100px); /* Adjust based on your navbar height */
}

/* Second row with 70/30 split */
.container-fluid.py-4 .row:nth-child(2) {
    flex: 1;
    display: flex;
    overflow: hidden; /* Prevent entire page scroll */
}

/* Left column - scrollable */
.col-lg-8 {
    height: 100%;
    overflow-y: auto;
    padding-right: 15px;
}

/* Right column - fixed map */
.col-lg-4 {
    height: 100%;
    position: sticky;
    top: 0;
}

/* Map container adjustments */
.map-container {
    height: 100%;
    position: sticky;
    top: 0;
}

#map {
    height: 100% !important;
    position: sticky;
    top: 0;
}

/* Ensure property cards grid doesn't overflow */
.col-lg-8 .row {
    margin-right: 0;
    margin-left: 0;
}

/* Optional: Add some spacing between columns */
.col-lg-8 {
    padding-right: 20px;
}

.col-lg-4 {
    padding-left: 10px;
}

/* Mobile responsiveness */
@media (max-width: 991px) {
    .container-fluid.py-4 .row:nth-child(2) {
        flex-direction: column;
    }
    
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
        position: static;
        height: auto;
    }
    
    .col-lg-8 {
        padding-right: 0;
        margin-bottom: 20px;
    }
    
    .col-lg-4 {
        padding-left: 0;
    }
    
    .map-container {
        height: 400px;
        position: static;
    }
}
    </style>
</head>
<body>
<!-- Always include both -->
<div class="nav-desktop">
  <?php include 'navbar.php'; ?>
</div>

<div class="nav-mobile">
  <?php include 'nav-mobile.php'; ?>
</div>

<style>
/* Hide one based on screen size */
.nav-mobile { display: none; }
@media (max-width: 991px) {
  .nav-desktop { display: none; }
  .nav-mobile { display: block; }
}
</style>

<!-- Main Content -->
<div class="container-fluid py-4" style="margin-top: 100px;">
    <!-- First Row: Search Filter -->
    <div class="row">
        <div class="col-12">
            <div class="search-container">
                <form method="GET" action="property.php">
                    <div class="search-row">
                        <div class="search-field">
                            <label for="location">Where are you going? *</label>
                            <input type="text" id="location" name="location" class="search-input" placeholder="Enter city or street" value="<?php echo htmlspecialchars($location); ?>">
                        </div>
                        
                        <div class="search-field">
                            <label for="move-in-date">Move in date</label>
                            <input type="text" id="move-in-date" class="search-input datepicker" placeholder="Select date">
                        </div>
                        
                        <div class="search-field">
                            <label for="budget">Monthly budget</label>
                            <input type="text" id="budget" class="search-input" placeholder="Enter budget">
                        </div>
                        
                        <div class="search-field">
                            <label for="beds-baths">Beds & baths</label>
                            <select id="beds-baths" class="search-input">
                                <option>All options</option>
                                <option>Studio</option>
                                <option>1 bed, 1 bath</option>
                                <option>2 beds, 1 bath</option>
                                <option>2 beds, 2 baths</option>
                                <option>3+ beds, 2+ baths</option>
                            </select>
                        </div>
                        
                        <div class="search-field">
                            <label>&nbsp;</label>
                            <button type="submit" class="search-btn" style="background-color: #00524e;">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                        
                        <div class="search-field">
                            <label>&nbsp;</label>
                            <button type="button" class="search-btn" style="background-color: transparent; color: #00524e; border: 1px solid #00524e;">
                                <i class="fas fa-filter"></i> Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Second Row: 70/30 Split -->
    <div class="row">
        <!-- Left Column (70%) -->
        <div class="col-lg-8">
            <!-- First Row: Results Header -->
            <div class="results-header">
                <div class="results-count">
                    <?php 
                    if ($propertyCount > 0) {
                        echo $propertyCount . " furnished monthly rentals near " . htmlspecialchars($location);
                    } else {
                        echo "No property found near " . htmlspecialchars($location);
                    }
                    ?>
                </div>
                <?php if ($propertyCount > 0): ?>
                <button class="save-search"><i class="fas fa-bookmark"></i> Save this search</button>
                <?php endif; ?>
            </div>
            
            <!-- Second Row: Property Cards Grid -->
            <div class="row">
                <?php if ($propertyCount > 0): ?>
                    <?php foreach ($properties as $property): ?>
                        <div class="col-md-6 col-lg-4">
                           <a href="view_property.php?property_code=<?php echo urlencode($property['property_code']); ?>" class="property-card text-decoration-none text-dark">

                                <div class="property-image-container">
                                    <div class="image-slider" data-property-code="<?php echo $property['property_code']; ?>">
                                        <?php if (!empty($property['images'])): ?>
                                            <img src="<?php echo $property['images'][0]['image_path']; ?>" alt="<?php echo htmlspecialchars($property['property_title'] ?: 'Property'); ?>" class="property-image">
                                            <?php if (count($property['images']) > 1): ?>
                                                <button class="slider-nav prev-btn"><i class="fas fa-chevron-left"></i></button>
                                                <button class="slider-nav next-btn"><i class="fas fa-chevron-right"></i></button>
                                                <div class="image-counter">1/<?php echo count($property['images']); ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <!-- Fallback placeholder image -->
                                            <img src="uploads/default-property.image.jpg" alt="<?php echo htmlspecialchars($property['property_title'] ?: 'Property'); ?>" class="property-image">
                                            <div class="image-counter">No images</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="property-badge">$<?php echo number_format($property['monthly_rent']); ?>/month</div>
                                </div>
                                <div class="property-details">
                                    <div class="property-type">
                                        <i class="fas fa-<?php echo $property['place_access_type'] == 'Private room' ? 'door-open' : 'home'; ?>"></i> 
                                        <?php echo htmlspecialchars($property['place_access_type'] ?: 'Entire place'); ?>
                                    </div>
                                    <h3 class="property-title"><?php echo htmlspecialchars($property['property_title'] ?: $property['property_type'] . ' in ' . $property['city']); ?></h3>
                                    <div class="property-location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php 
                                        $locationParts = [];
                                        if (!empty($property['street'])) $locationParts[] = $property['street'];
                                        if (!empty($property['city'])) $locationParts[] = $property['city'];
                                        if (!empty($property['province'])) $locationParts[] = $property['province'];
                                        echo htmlspecialchars(implode(', ', $locationParts));
                                        ?>
                                    </div>
                                    <div class="property-status">
                                        Available <?php echo !empty($property['available_on']) ? date('M j, Y', strtotime($property['available_on'])) : 'Now'; ?>
                                    </div>
                                    <div class="property-features">
                                        <?php if ($property['beds'] > 0): ?>
                                        <div class="feature">
                                            <i class="fas fa-bed"></i> <?php echo $property['beds']; ?> Beds
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($property['baths'] > 0): ?>
                                        <div class="feature">
                                            <i class="fas fa-bath"></i> <?php echo $property['baths']; ?> Baths
                                        </div>
                                        <?php endif; ?>
                                        <div class="feature">
                                            <i class="fas fa-home"></i> <?php echo htmlspecialchars($property['property_type']); ?>
                                        </div>
                                    </div>
                                    <div class="property-amenities">
                                        <?php if (!empty($property['furnishing']) && $property['furnishing'] != 'Unfurnished'): ?>
                                        <div class="amenity">
                                            <i class="fas fa-couch"></i> <?php echo htmlspecialchars($property['furnishing']); ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($property['pets_allowed'] == 'Yes'): ?>
                                        <div class="amenity">
                                            <i class="fas fa-paw"></i> Pet Friendly
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($property['parking_spaces']) && $property['parking_spaces'] > 0): ?>
                                        <div class="amenity">
                                            <i class="fas fa-car"></i> Parking
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($property['utilities'])): ?>
                                        <div class="amenity">
                                            <i class="fas fa-bolt"></i> Utilities
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="no-results">
                            <i class="fas fa-home"></i>
                            <h3>No property found</h3>
                            <p>We couldn't find any property matching your search criteria.</p>
                            <p>Try adjusting your filters or search in a different location.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($propertyCount > 0): ?>
            <nav aria-label="Property pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
        
        <!-- Right Column (30%) - Map -->
             <div class="col-lg-4">
            <div class="map-container">
                <button class="fullscreen-toggle" id="fullscreen-toggle">
                    <i class="fas fa-expand"></i> Full Screen
                </button>
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Map Overlay -->
<div id="fullscreen-map" class="map-fullscreen" style="display: none;">
    <button class="close-fullscreen" id="close-fullscreen">
        <i class="fas fa-times"></i> Close
    </button>
    <div id="fullscreen-map-container"></div>
</div>

<!-- Footer -->
<?php include 'footer.php';?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    // Initialize map with property locations
    function initMap(containerId) {
        // Get properties data from PHP
        const properties = <?php echo json_encode($properties); ?>;
        
        // Determine map center based on search location
        let mapCenter = [39.8283, -98.5795]; // Default center of US
        let zoomLevel = 4;
        
        <?php if (!empty($location)): ?>
            // Try to get coordinates for the searched city
            const searchedCity = "<?php echo $location; ?>";
            const cityCoordinates = {
                'new york': [40.7128, -74.0060],
                'los angeles': [34.0522, -118.2437],
                'chicago': [41.8781, -87.6298],
                'houston': [29.7604, -95.3698],
                'phoenix': [33.4484, -112.0740],
                'philadelphia': [39.9526, -75.1652],
                'san antonio': [29.4241, -98.4936],
                'san diego': [32.7157, -117.1611],
                'dallas': [32.7767, -96.7970],
                'san jose': [37.3382, -121.8863],
                'austin': [30.2672, -97.7431],
                'jacksonville': [30.3322, -81.6557],
                'fort worth': [32.7555, -97.3308],
                'columbus': [39.9612, -82.9988],
                'charlotte': [35.2271, -80.8431],
                'san francisco': [37.7749, -122.4194],
                'indianapolis': [39.7684, -86.1581],
                'seattle': [47.6062, -122.3321],
                'denver': [39.7392, -104.9903],
                'washington': [38.9072, -77.0369],
                'boston': [42.3601, -71.0589],
                'nashville': [36.1627, -86.7816],
                'baltimore': [39.2904, -76.6122],
                'portland': [45.5152, -122.6784],
                'las vegas': [36.1699, -115.1398],
                'milwaukee': [43.0389, -87.9065],
                'albuquerque': [35.0844, -106.6504],
                'tucson': [32.2226, -110.9747],
                'fresno': [36.7378, -119.7871],
                'sacramento': [38.5816, -121.4944]
            };
            
            const cityLower = searchedCity.toLowerCase().trim();
            if (cityCoordinates[cityLower]) {
                mapCenter = cityCoordinates[cityLower];
                zoomLevel = 12;
            }
        <?php endif; ?>
        
        // If we have properties with coordinates, use the first one as center
        if (properties.length > 0) {
            const firstProperty = properties[0];
            if (firstProperty.latitude && firstProperty.longitude && 
                firstProperty.latitude != 0 && firstProperty.longitude != 0) {
                mapCenter = [parseFloat(firstProperty.latitude), parseFloat(firstProperty.longitude)];
                zoomLevel = 12;
            }
        }
        
        const map = L.map(containerId).setView(mapCenter, zoomLevel);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add markers for properties
        properties.forEach(property => {
            let markerLatLng;
            
            // Use coordinates if available
            if (property.latitude && property.longitude && property.latitude != 0 && property.longitude != 0) {
                markerLatLng = [parseFloat(property.latitude), parseFloat(property.longitude)];
            } else {
                // Fallback: Use city coordinates
                const cityLower = property.city ? property.city.toLowerCase().trim() : '';
                if (cityLower && cityCoordinates[cityLower]) {
                    markerLatLng = cityCoordinates[cityLower];
                } else {
                    // Final fallback: Use map center
                    markerLatLng = mapCenter;
                }
            }
            
            const marker = L.marker(markerLatLng).addTo(map);
            marker.bindPopup(`
                <div class="property-popup">
                    <h4>${property.property_title || property.property_type + ' in ' + property.city}</h4>
                    <p><strong>Price:</strong> $${property.monthly_rent ? Number(property.monthly_rent).toLocaleString() : 'N/A'}/month</p>
                    <p><strong>Type:</strong> ${property.property_type}</p>
                    <p><strong>Location:</strong> ${property.street ? property.street + ', ' : ''}${property.city}, ${property.province}</p>
                    <button class="btn btn-sm btn-primary">View Details</button>
                </div>
            `);
        });
        
        return map;
    }

    // Initialize the main map
    const map = initMap('map');
    
    // Fullscreen map functionality
    // Fullscreen map functionality
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    const closeFullscreen = document.getElementById('close-fullscreen');
    const fullscreenMap = document.getElementById('fullscreen-map');
    const fullscreenMapContainer = document.getElementById('fullscreen-map-container');
    
    if (fullscreenToggle && closeFullscreen) {
        fullscreenToggle.addEventListener('click', function() {
            fullscreenMap.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Initialize the fullscreen map
            setTimeout(() => {
                initMap('fullscreen-map-container');
            }, 100);
        });
        
        closeFullscreen.addEventListener('click', function() {
            fullscreenMap.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    }
    
    // Image slider functionality
    document.querySelectorAll('.image-slider').forEach(slider => {
        const propertyCode = slider.getAttribute('data-property-code');
        const imageElement = slider.querySelector('img');
        const counter = slider.querySelector('.image-counter');
        const prevBtn = slider.querySelector('.prev-btn');
        const nextBtn = slider.querySelector('.next-btn');
        
        // Get images for this specific property from PHP data
        const properties = <?php echo json_encode($properties); ?>;
        const property = properties.find(p => p.property_code === propertyCode);
        
        if (!property || !property.images || property.images.length === 0) {
            return; // No images to slide through
        }
        
        const images = property.images.map(img => img.image_path);
        let currentIndex = 0;
        
        function updateImage() {
            imageElement.src = images[currentIndex];
            if (counter) {
                counter.textContent = `${currentIndex + 1}/${images.length}`;
            }
        }
        
        if (prevBtn && nextBtn && images.length > 1) {
            prevBtn.addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                updateImage();
            });
            
            nextBtn.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % images.length;
                updateImage();
            });
        }
    });
</script>

</body>
</html>