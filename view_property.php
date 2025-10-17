<?php
// view_property.php
// Requires: includes/db.php (which provides $conn), navbar.php, nav-mobile.php, footer.php
// Usage: view_property.php?property_code=XXXX or view_property.php?intake_id=123

include "includes/db.php"; // MUST provide $conn

// Get property_code from GET
$propertyCode = isset($_GET['property_code']) ? $_GET['property_code'] : (isset($_GET['intake_id']) ? $_GET['intake_id'] : '');

// Optional lat/lng from URL to pinpoint on map (overrides property coords)
$urlLat = isset($_GET['lat']) ? $_GET['lat'] : null;
$urlLng = isset($_GET['lng']) ? $_GET['lng'] : null;

// Initialize containers
$property = null;
$images = [];
$amenities = [];

// Fetch property using prepared statement
if (!empty($propertyCode)) {
    $sql = "SELECT * FROM property WHERE property_code = ? OR intake_id = ? OR account_number = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $propertyCode, $propertyCode, $propertyCode);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $property = $res->fetch_assoc();
    }
    $stmt->close();
}

// Fetch images for property_images table
if ($property) {
    $imgSql = "SELECT * FROM property_images WHERE property_code = ? ORDER BY image_order ASC, id ASC";
    $imgStmt = $conn->prepare($imgSql);
    $imgStmt->bind_param("s", $property['property_code']);
    $imgStmt->execute();
    $imgRes = $imgStmt->get_result();
    while ($r = $imgRes->fetch_assoc()) {
        $images[] = $r;
    }
    $imgStmt->close();

    // Fetch amenities
    $amSql = "SELECT * FROM property_amenities WHERE property_code = ? ORDER BY id ASC";
    $amStmt = $conn->prepare($amSql);
    $amStmt->bind_param("s", $property['property_code']);
    $amStmt->execute();
    $amRes = $amStmt->get_result();
    while ($a = $amRes->fetch_assoc()) {
        $amenities[] = $a;
    }
    $amStmt->close();
}

// Prepare JSON for JS
$propertyJson = $property ? json_encode($property) : 'null';
$imagesJson = json_encode($images);

// Your color palette
$primary = '#340f3b';
$accent = '#4f145c';
$accent2 = '#3b1453';
$mutedGray = '#003366';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo $property ? htmlspecialchars($property['property_title']) : 'Property Details'; ?></title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <style>
    :root{
      --primary: <?php echo $primary; ?>;
      --accent: <?php echo $accent; ?>;
      --accent2: <?php echo $accent2; ?>;
      --muted-gray: <?php echo $mutedGray; ?>;
      --card-radius: .5rem;
      --border-color: #e5e7eb;
    }
    
    body{ 
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; 
      background:#fafbfc; 
      color:#1f2937; 
      line-height: 1.6;
    }

    /* Reduced top spacing */
    .page-container { margin-top: 70px; }

    /* Image layout: left 2x2 grid, right large image */
    .images-grid { 
      display:grid; 
      grid-template-columns:1fr 1fr; 
      gap:10px; 
      margin-bottom: 2rem;
    }
    .images-left { 
      display:grid; 
      grid-template-columns:1fr 1fr; 
      gap:6px; 
      grid-auto-rows:130px; 
    }
    .images-left img, .images-right img { 
      width:100%; 
      height:100%; 
      object-fit:cover; 
      border-radius:var(--card-radius); 
      border: 1px solid var(--border-color);
    }
    .images-right{ 
      grid-row:span 2; 
      height:100%; 
      min-height: 260px; 
    }

    @media (max-width: 991px){
      .images-grid{ grid-template-columns:1fr; }
      .images-right{ grid-row:auto; min-height: 240px; }
      .images-left{ grid-auto-rows:110px; }
    }

    /* Sleek card design */
    .content-card {
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      background: transparent;
      padding: 1.25rem;
      margin-bottom: 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
      transition: box-shadow 0.2s ease;
    }

    .content-card:hover {
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    /* Sticky booking card */
    .sticky-card{ 
      position:sticky; 
      top:90px; 
      border-radius: var(--card-radius);
      padding:1.25rem; 
      background:transparent; 
      border: 1px solid var(--border-color);
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      margin-bottom: 1rem;
    }
    .sticky-card.stopped{ position:relative !important; top: auto !important; }

    .price { 
      font-size:1.75rem; 
      font-weight:700; 
      color:var(--primary); 
      letter-spacing: -0.025em;
    }
    .muted { color:#6b7280; font-size: 0.875rem; }

    /* Improved amenity pills */
    .amenity-pill{ 
      display:inline-flex; 
      gap:6px; 
      align-items:center; 
      padding:6px 10px; 
      border-radius:16px; 
      border:1px solid var(--border-color); 
      background:transparent; 
      margin:2px; 
      font-size:.85rem; 
      color: #4b5563;
    }

    /* Map container */
    #map { 
      height:320px; 
      width:100%; 
      border-radius: var(--card-radius); 
      border: 1px solid var(--border-color);
    }

    /* Typography improvements */
    .meta-small{ font-size:.875rem; color:#6b7280; }
    
    h2 { 
      font-size: 1.5rem; 
      font-weight: 600; 
      color: #111827; 
      margin-bottom: 0.5rem;
    }
    
    h5 {
      font-size: 1.125rem;
      font-weight: 600;
      color: #111827;
      margin-bottom: 0.75rem;
    }
    
    h6 {
      font-size: 1rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.5rem;
    }

    /* Buttons */
    .btn-primary-custom{ 
      background:linear-gradient(90deg,var(--accent2),var(--accent)); 
      border:none; 
      color:#fff; 
      font-weight: 500;
      padding: 0.625rem 1rem;
    }
    
    .btn-outline-secondary, .btn-outline-dark {
      border: 1px solid var(--border-color);
      color: #6b7280;
      font-weight: 500;
      padding: 0.625rem 1rem;
    }
    
    /* Leaflet popup styling */
    .leaflet-popup-content-wrapper {
      border-radius: 6px;
    }
    .leaflet-popup-content {
      margin: 10px 14px;
      line-height: 1.4;
    }

    /* Fees section in right column */
    .fees-section { display: none; }
    .fees-section.visible { display: block; }

    /* Reduced spacing in main content */
    .row.g-4 {
      --bs-gutter-x: 1.5rem;
      --bs-gutter-y: 1rem;
    }

    /* Improved list styling */
    ul:not(.list-unstyled) {
      padding-left: 1.25rem;
      margin-bottom: 0;
    }
    
    ul:not(.list-unstyled) li {
      margin-bottom: 0.25rem;
      color: #4b5563;
    }

    /* Badge improvements */
    .badge {
      font-weight: 500;
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }

    /* Room cards */
    .room-card {
      border: 1px solid var(--border-color);
      border-radius: 6px;
      padding: 0.75rem;
      background: transparent;
      margin-bottom: 0.5rem;
    }

    /* Reduced container padding */
    .container {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    @media (min-width: 768px) {
      .container {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <!-- navbar includes (optional) -->
  <div class="nav-desktop"><?php include 'navbar.php'; ?></div>
  <div class="nav-mobile"><?php include 'nav-mobile.php'; ?></div>
  <style>.nav-mobile{display:none;} @media (max-width:991px){.nav-desktop{display:none}.nav-mobile{display:block}}</style>

  <div class="container page-container">
    <?php if (!$property): ?>
      <div class="alert alert-warning">No property found. Pass <code>?property_code=...</code> in the URL.</div>
    <?php else: ?>

    <!-- FIRST ROW: Images -->
    <div class="images-grid">
      <div class="images-left">
        <?php
          // Display first 4 images or placeholders
          for ($i = 0; $i < 4; $i++):
            $imgSrc = isset($images[$i]) && !empty($images[$i]['image_path']) ? $images[$i]['image_path'] : 'uploads/default-property.image.jpg';
        ?>
          <div><img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="property-image-<?php echo $i; ?>" loading="lazy"></div>
        <?php endfor; ?>
      </div>

      <div class="images-right">
        <?php
          $rightImg = isset($images[4]) && !empty($images[4]['image_path']) ? $images[4]['image_path'] : (isset($images[0]) ? $images[0]['image_path'] : 'uploads/default-property.image.jpg');
        ?>
        <img src="<?php echo htmlspecialchars($rightImg); ?>" alt="property-main-image" loading="lazy">
      </div>
    </div>

    <!-- SECOND ROW: Details (left) + Sticky booking card (right) -->
    <div class="row g-4">
      <div class="col-lg-8">

        <!-- Title card -->
        <div class="content-card">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <h2 class="mb-1"><?php echo htmlspecialchars($property['property_title'] ?: 'Property Title'); ?></h2>
              <div class="meta-small mb-2">Last updated <?php echo !empty($property['date_created']) ? date('m.d.Y', strtotime($property['date_created'])) : date('m.d.Y'); ?></div>
              <div class="muted mb-2">Property ID: <?php echo htmlspecialchars($property['property_code']); ?></div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success">Available</span>
                <small class="muted">House in <?php echo htmlspecialchars($property['city'] . ', ' . $property['province']); ?></small>
              </div>
            </div>
            <div class="text-end ms-3">
              <div class="muted small">Landlord Tenure: 2 years, 10 months</div>
              <div class="mt-1"><i class="fas fa-star text-warning"></i> <small class="muted">(0 reviews)</small></div>
            </div>
          </div>
        </div>

        <!-- Quick facts -->
        <div class="content-card">
          <div class="row text-center">
            <div class="col-4">
              <strong class="d-block"><?php echo htmlspecialchars($property['guests'] ?: '—'); ?></strong>
              <div class="muted small">Guests</div>
            </div>
            <div class="col-4">
              <strong class="d-block"><?php echo htmlspecialchars($property['bedrooms'] ?: $property['beds'] ?: '—'); ?></strong>
              <div class="muted small">Bedrooms</div>
            </div>
            <div class="col-4">
              <strong class="d-block"><?php echo htmlspecialchars($property['baths'] ?: '—'); ?></strong>
              <div class="muted small">Bathrooms</div>
            </div>
          </div>
        </div>

        <!-- Closest facilities -->
        <div class="content-card">
          <h5>Closest facilities</h5>
          <ul>
            <li>Robert Wood Johnson University Hospital - 1.34 miles away</li>
            <li>Childrens Specialized Hospital - 1.34 miles away</li>
            <li>Saint Peters University Hospital - 1.93 miles away</li>
            <li>Piscataway Cboc - 5.65 miles away</li>
            <li>Jfk Medical Ctr - 7.45 miles away</li>
          </ul>
        </div>

        <!-- Amenities -->
        <div class="content-card">
          <h5>What this property offers</h5>
          <div class="mt-2">
            <?php if (!empty($amenities)): ?>
              <?php foreach ($amenities as $am): ?>
                <span class="amenity-pill"><i class="fas fa-check text-success"></i> <?php echo htmlspecialchars($am['amenity_name']); ?></span>
              <?php endforeach; ?>
            <?php else: ?>
              <!-- fallback using property columns -->
              <?php if (!empty($property['appliances'])): ?>
                <span class="amenity-pill"><i class="fas fa-utensils"></i> Appliances</span>
              <?php endif; ?>
              <?php if (!empty($property['furnishing']) && $property['furnishing'] !== 'Unfurnished'): ?>
                <span class="amenity-pill"><i class="fas fa-couch"></i> <?php echo htmlspecialchars($property['furnishing']); ?></span>
              <?php endif; ?>
              <?php if (!empty($property['parking_spaces'])): ?>
                <span class="amenity-pill"><i class="fas fa-car"></i> Parking</span>
              <?php endif; ?>
              <?php if (!empty($property['pets_allowed']) && strtolower($property['pets_allowed']) === 'yes'): ?>
                <span class="amenity-pill"><i class="fas fa-paw"></i> Pet Friendly</span>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Description -->
        <div class="content-card">
          <h5>Overview</h5>
          <p class="mb-0"><?php echo nl2br(htmlspecialchars($property['description'] ?: 'No description available.')); ?></p>
        </div>

        <!-- Rooms & beds anchor -->
        <div id="rooms-anchor" class="content-card">
          <h5>Space</h5>
          <p class="muted mb-3">Can fit <?php echo htmlspecialchars($property['guests'] ?: '—'); ?> people comfortably</p>
          <h6>Rooms & beds</h6>

          <div class="row">
            <?php
              $bedCount = intval($property['bedrooms'] ?: $property['beds'] ?: 0);
              if ($bedCount <= 0) $bedCount = 3;
              for ($i = 1; $i <= $bedCount; $i++):
            ?>
              <div class="col-md-4">
                <div class="room-card">
                  <strong class="d-block">Bedroom <?php echo $i; ?></strong>
                  <small class="muted">
                    <?php
                      if (!empty($property['bed_sizes'])) {
                          $sizes = explode(',', $property['bed_sizes']);
                          echo htmlspecialchars(trim($sizes[$i-1] ?? $sizes[0] ?? 'Queen Bed'));
                      } else {
                          echo ($i < 3) ? 'Queen Bed' : 'Double (Full) Bed';
                      }
                    ?>
                  </small>
                </div>
              </div>
            <?php endfor; ?>
          </div>

          <div class="mt-3">
            <strong><?php echo htmlspecialchars($property['baths'] ?: '1'); ?> bathroom</strong>
            <div class="muted">Bathroom 1 - Private Bath</div>
          </div>
        </div>

        <!-- Fees anchor -->
        <div id="fees-anchor" class="content-card">
          <h5>Property fees</h5>
          <ul>
            <?php
              $security_deposit = $property['security_deposit'] ?? null;
              $move_in_fees = $property['move_in_fees'] ?? null;
              $pet_deposit = $property['pet_deposit'] ?? null;

              function safe_number_format($value, $fallback = 0) {
                  return is_numeric($value) ? number_format((float)$value) : number_format((float)$fallback);
              }

              $hasFees = is_numeric($security_deposit) || is_numeric($move_in_fees) || is_numeric($pet_deposit);

              if ($hasFees) {
            ?>
              <li>Deposit (Refundable): $<?php echo htmlspecialchars(safe_number_format($security_deposit, 1700)); ?></li>
              <li>Cleaning Fee: $<?php echo htmlspecialchars(safe_number_format($move_in_fees, 150)); ?></li>
              <li>Pet Deposit (Non-Refundable): $<?php echo htmlspecialchars(safe_number_format($pet_deposit, 500)); ?></li>
            <?php } else { ?>
              <li>Deposit (Refundable): $2,000</li>
              <li>Cleaning Fee: $150</li>
              <li>Pet Deposit (Non-Refundable): $500</li>
            <?php } ?>
          </ul>
        </div>

        <!-- Map -->
        <div class="content-card">
          <h5>Location</h5>
          <div id="map"></div>
        </div>

        <!-- Reviews -->
        <div class="content-card">
          <h5>Reviews</h5>
          <p class="muted mb-2">Be the first to leave a review for this property after your stay.</p>
          <a href="#" class="btn btn-outline-secondary btn-sm">Write your review</a>
        </div>

        <!-- Landlord -->
        <div class="content-card">
          <h5>About the landlord</h5>
          <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-weight:600;color:#6b7280;">C</div>
            <div class="flex-grow-1">
              <div class="fw-bold">Cici</div>
              <div class="muted small">Tenure: 2 years, 10 months</div>
              <div class="muted small">New Jersey, NJ</div>
              <div class="mt-1">
                <span class="badge bg-success">Email verified</span>
                <span class="badge bg-success">Phone verified</span>
                <span class="badge bg-success">ID verified</span>
              </div>
            </div>
            <div>
              <a href="#" class="btn btn-outline-primary btn-sm">Message</a>
            </div>
          </div>
        </div>

      </div> <!-- /col-lg-8 -->

      <!-- RIGHT: Sticky booking card -->
      <div class="col-lg-4">
        <div id="booking-card" class="sticky-card">
          <div class="text-center mb-3">
            <div class="price">$<?php echo number_format($property['monthly_rent'] ?? 3600); ?> <small class="muted">/month</small></div>
            <div class="muted small">Utilities: <?php echo !empty($property['utilities']) ? htmlspecialchars($property['utilities']) : 'included'; ?></div>
            <div class="muted small">Minimum stay: 1 month</div>
            <div class="muted small">Status: <span class="text-success">Available</span></div>
          </div>

          <hr class="my-3">

          <!-- Fees section that appears when scrolling -->
          <div id="fees-section" class="fees-section">
            <strong class="small">Property fees</strong>
            <ul class="list-unstyled mt-2 small">
              <li class="d-flex justify-content-between">
                <span>Deposit:</span>
                <strong>$<?php echo number_format($property['security_deposit'] ?? 1700); ?></strong>
              </li>
              <li class="d-flex justify-content-between">
                <span>Cleaning Fee:</span>
                <strong>$<?php echo number_format($property['move_in_fees'] ?? 150); ?></strong>
              </li>
              <li class="d-flex justify-content-between">
                <span>Pet Deposit:</span>
                <strong>$<?php echo number_format($property['pet_deposit'] ?? 500); ?></strong>
              </li>
            </ul>
            <hr class="my-3">
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary btn-primary-custom">Send Booking Request</button>
            <a href="#" class="btn btn-outline-secondary">Message Landlord</a>
            <a href="tel:<?php echo htmlspecialchars($property['account_number'] ?? ''); ?>" class="btn btn-outline-dark">Call Landlord</a>
            <button class="btn btn-link text-muted p-0 small">Report This Property</button>
          </div>
        </div>
      </div>

    </div> <!-- /row -->

    <?php endif; ?>
  </div> <!-- /container -->

  <?php include 'footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    // Data from PHP
    const property = <?php echo $propertyJson; ?>;
    const images = <?php echo $imagesJson; ?>;
    const urlLat = <?php echo $urlLat !== null ? json_encode($urlLat) : 'null'; ?>;
    const urlLng = <?php echo $urlLng !== null ? json_encode($urlLng) : 'null'; ?>;

    // Initialize Leaflet Map
    (function initMap() {
      const mapEl = document.getElementById('map');
      if (!mapEl) return;

      let lat = null, lng = null;
      if (urlLat && urlLng) {
        lat = parseFloat(urlLat); 
        lng = parseFloat(urlLng);
      } else if (property && property.latitude && property.longitude && property.latitude != 0 && property.longitude != 0) {
        lat = parseFloat(property.latitude); 
        lng = parseFloat(property.longitude);
      } else if (property && property.city) {
        const cityCoords = {
          'new brunswick': {lat:40.4862, lng:-74.4518},
          'new york': {lat:40.7128, lng:-74.0060},
          'los angeles': {lat:34.0522, lng:-118.2437},
          'san diego': {lat:32.7157, lng:-117.1611},
          'austin': {lat:30.2672, lng:-97.7431},
          'seattle': {lat:47.6062, lng:-122.3321},
          'boston': {lat:42.3601, lng:-71.0589},
          'denver': {lat:39.7392, lng:-104.9903},
          'houston': {lat:29.7604, lng:-95.3698},
          'chicago': {lat:41.8781, lng:-87.6298}
        };
        const key = property.city.toLowerCase().trim();
        if (cityCoords[key]) { 
          lat = cityCoords[key].lat; 
          lng = cityCoords[key].lng; 
        }
      }

      if (!lat || !lng) {
        lat = 39.8283; 
        lng = -98.5795;
      }

      const map = L.map('map').setView([lat, lng], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      const customIcon = L.divIcon({
        html: '<i class="fas fa-home" style="color: #4f145c; font-size: 20px;"></i>',
        iconSize: [20, 20],
        className: 'custom-leaflet-icon'
      });

      const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

      let popupContent = `<div style="max-width:220px;"><strong>${property && property.property_title ? property.property_title : 'Property'}</strong><br>`;
      if (property && property.monthly_rent) popupContent += `<small>Price: $${Number(property.monthly_rent).toLocaleString()}/month</small><br>`;
      if (property && property.street) popupContent += `<small>${property.street}, ${property.city}</small>`;
      popupContent += `</div>`;

      marker.bindPopup(popupContent);
    })();

    // Stop sticky when Rooms anchor visible
    (function stickyStopper() {
      const bookingCard = document.getElementById('booking-card');
      const roomsAnchor = document.getElementById('rooms-anchor');
      if (!bookingCard || !roomsAnchor) return;

      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            bookingCard.classList.add('stopped');
          } else {
            bookingCard.classList.remove('stopped');
          }
        });
      }, { threshold: 0.02 });

      observer.observe(roomsAnchor);
    })();

    // Show fees in right column when fees section is visible
    (function feesSectionObserver() {
      const feesSection = document.getElementById('fees-section');
      const feesAnchor = document.getElementById('fees-anchor');
      if (!feesSection || !feesAnchor) return;

      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            feesSection.classList.add('visible');
          } else {
            feesSection.classList.remove('visible');
          }
        });
      }, { threshold: 0.1 });

      observer.observe(feesAnchor);
    })();

    // Image interaction
    (function imagesInteraction(){
      const leftImgs = document.querySelectorAll('.images-left img');
      const rightImg = document.querySelector('.images-right img');
      if (!rightImg || !leftImgs.length) return;
      leftImgs.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => {
          const tmp = rightImg.src;
          rightImg.src = img.src;
          img.src = tmp;
        });
      });
    })();
  </script>
</body>
</html>