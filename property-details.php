<?php
// property.php
// Requires: includes/db.php, navbar.php, nav-mobile.php, footer.php
// Assumptions: main property table is `property` and images are in `property_images` with property_code/key

include "includes/db.php";

// Get property_code from GET (or fallback to search location)
$propertyCode = isset($_GET['property_code']) ? $_GET['property_code'] : (isset($_GET['propid']) ? $_GET['propid'] : '');

// Optional lat/lng from URL to pinpoint on map
$urlLat = isset($_GET['lat']) ? $_GET['lat'] : null;
$urlLng = isset($_GET['lng']) ? $_GET['lng'] : null;

// Fetch property
$property = null;
if (!empty($propertyCode)) {
    $sql = "SELECT * FROM property WHERE property_code = ? OR propid = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $propertyCode, $propertyCode);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $property = $res->fetch_assoc();
    }
    $stmt->close();
}

// fetch images for this property (up to 5 recommended)
$images = [];
if ($property) {
    $imageSql = "SELECT * FROM property_images WHERE property_code = ? ORDER BY image_order ASC LIMIT 8";
    $imgStmt = $conn->prepare($imageSql);
    $imgStmt->bind_param("s", $property['property_code']);
    $imgStmt->execute();
    $imgRes = $imgStmt->get_result();
    while ($r = $imgRes->fetch_assoc()) {
        $images[] = $r;
    }
    $imgStmt->close();
}

// Simple city geocode table (fallback if lat/lng missing)
function getCityCoordinates($city) {
    $cityCoordinates = [
        'new york' => [40.7128, -74.0060],
        'los angeles' => [34.0522, -118.2437],
        'chicago' => [41.8781, -87.6298],
        'houston' => [29.7604, -95.3698],
        'new brunswick' => [40.4862, -74.4518],
        // ... add more if needed
    ];
    $cityLower = strtolower(trim($city));
    return isset($cityCoordinates[$cityLower]) ? $cityCoordinates[$cityLower] : null;
}

// Prepare data for JS
$propertyJson = $property ? json_encode($property) : 'null';
$imagesJson = json_encode($images);

// Colors (your palette)
$primary = '#340f3b';
$accent = '#4f145c';
$accent2 = '#3b1453';
$gray = '#003366';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $property ? htmlspecialchars($property['property_title']) : 'Property'; ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

  <style>
    :root {
      --primary: <?php echo $primary; ?>;
      --accent: <?php echo $accent; ?>;
      --accent2: <?php echo $accent2; ?>;
      --muted-gray: <?php echo $gray; ?>;
      --card-radius: .75rem;
    }

    body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f7f9fb; color:#222; }

    /* Search header */
    .search-top { margin-top: 100px; }

    /* Images layout (first row) */
    .images-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    /* Left side 2x2 -> use nested grid */
    .images-left {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-auto-rows: 140px;
      gap: 8px;
    }
    .images-left img,
    .images-right img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: var(--card-radius);
      box-shadow: 0 6px 18px rgba(17,17,17,0.08);
    }
    .images-right {
      grid-row: span 2;
      height: 100%;
    }
    @media (max-width: 991px) {
      .images-grid { grid-template-columns: 1fr; }
      .images-right { grid-row: auto; height: 300px; }
      .images-left { grid-auto-rows: 120px; }
    }

    /* Main layout second row */
    .content-left { }
    .sticky-card {
      position: sticky;
      top: 100px;
      border-radius: var(--card-radius);
      padding: 20px;
      background: #fff;
      box-shadow: 0 6px 20px rgba(16,24,40,0.06);
    }
    .stopped {
      position: relative !important;
    }

    /* Price */
    .price { font-size: 1.8rem; font-weight:700; color:var(--primary); }
    .muted { color:#6b7280; }

    /* Amenities pills */
    .amenity-pill { display:inline-flex; gap:8px; align-items:center; padding:8px 12px; border-radius:20px; border:1px solid #eee; background:#fbfcfd; margin:4px; font-size:0.9rem; }

    /* Rooms & beds anchor for observer */
    #rooms-anchor { margin-top: 60px; }

    /* small responsive tweaks */
    .property-meta { font-size:0.95rem; color:#334155; }

    /* Map */
    #property-map, #pin-map { height: 360px; width:100%; border-radius: .6rem; }

    /* Buttons */
    .btn-primary-custom {
      background: linear-gradient(90deg,var(--accent2),var(--accent));
      border: none;
    }
  </style>
</head>
<body>

  <!-- Navbar includes -->
  <div class="nav-desktop"><?php include 'navbar.php'; ?></div>
  <div class="nav-mobile"><?php include 'nav-mobile.php'; ?></div>
  <style>
    .nav-mobile { display: none; }
    @media (max-width: 991px) { .nav-desktop { display:none; } .nav-mobile { display:block; } }
  </style>

  <div class="container py-4 search-top">
    <!-- Top Search (kept for continuity) -->
    <form method="GET" action="property.php" class="mb-4">
      <div class="row g-2 align-items-end">
        <div class="col-md-5">
          <label class="form-label small">Where are you going?</label>
          <input type="text" name="location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>" class="form-control" placeholder="City or street">
        </div>
        <div class="col-md-2">
          <label class="form-label small">Move-in</label>
          <input class="form-control" type="date" name="move_in">
        </div>
        <div class="col-md-2">
          <label class="form-label small">Budget</label>
          <input class="form-control" type="text" name="budget" placeholder="$ / month">
        </div>
        <div class="col-md-2">
          <label class="form-label small">&nbsp;</label>
          <div>
            <button class="btn btn-primary btn-primary-custom"><i class="fas fa-search me-2"></i> Search</button>
            <a href="#" class="btn btn-outline-secondary ms-1"><i class="fas fa-filter"></i></a>
          </div>
        </div>
      </div>
    </form>

    <?php if (!$property): ?>
      <div class="alert alert-warning">No property found. Pass <code>?property_code=...</code> in the URL</div>
    <?php else: ?>

    <!-- FIRST ROW: Image layout (2x2 left, 1 big right) -->
    <div class="images-grid mb-4">
      <div class="images-left">
        <?php
          // show first 4 images on left (if less, show placeholders)
          for ($i = 0; $i < 4; $i++):
            $imgSrc = isset($images[$i]) && !empty($images[$i]['image_path']) ? $images[$i]['image_path'] : 'uploads/default-property.image.jpg';
        ?>
          <div class="p-0">
            <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="img-<?php echo $i; ?>">
          </div>
        <?php endfor; ?>
      </div>

      <div class="images-right">
        <?php
          // large image on right - use 5th image if available, else first
          $rightImg = isset($images[4]) && !empty($images[4]['image_path']) ? $images[4]['image_path'] : (isset($images[0]) ? $images[0]['image_path'] : 'uploads/default-property.image.jpg');
        ?>
        <img src="<?php echo htmlspecialchars($rightImg); ?>" alt="main-image">
      </div>
    </div>

    <!-- SECOND ROW: Two columns -->
    <div class="row g-4">
      <!-- LEFT: long description, overview, amenities, rooms, reviews, landlord -->
      <div class="col-lg-8 content-left">
        <div class="card p-4 mb-3" style="border-radius:.8rem;">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h2 class="mb-1"><?php echo htmlspecialchars($property['property_title'] ?: 'Excellent Arrangement! Entire House or Individual Rooms Available!'); ?></h2>
              <div class="muted mb-2">Last updated <?php
                  // if property has last_updated column, show date
                  if (!empty($property['last_updated'])) echo date('m.d.Y', strtotime($property['last_updated']));
                  else echo '06.30.2025';
              ?></div>
              <div class="property-meta mb-2">
                Property ID: <?php echo htmlspecialchars($property['property_code'] ?: ($property['propid'] ?? 'N/A')); ?>
              </div>
              <div class="mb-2"><span class="badge bg-success">Available</span> &nbsp; <small class="muted">House in <?php echo htmlspecialchars($property['city'] . ', ' . $property['province']); ?></small></div>
            </div>
            <div class="text-end">
              <div class="muted small">Landlord Tenure: 2 years, 10 months</div>
              <div class="mt-2"><i class="fas fa-star text-warning"></i> (0 reviews)</div>
            </div>
          </div>
        </div>

        <!-- Quick facts -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <div class="row">
            <div class="col-6">
              <strong>1005 Sq. Ft.</strong><div class="muted small"> Area</div>
            </div>
            <div class="col-3">
              <strong>3</strong><div class="muted small">Bedroom</div>
            </div>
            <div class="col-3">
              <strong>1</strong><div class="muted small">Bathroom</div>
            </div>
          </div>
        </div>

        <!-- Closest facilities -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>Closest facilities</h5>
          <ul class="list-unstyled mb-0">
            <li>Robert Wood Johnson University Hospital - 1.34 miles away</li>
            <li>Childrens Specialized Hospital - 1.34 miles away</li>
            <li>Saint Peters University Hospital - 1.93 miles away</li>
            <li>Piscataway Cboc - 5.65 miles away</li>
            <li>Jfk Medical Ctr - 7.45 miles away</li>
          </ul>
        </div>

        <!-- Amenities -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>What this property offers</h5>
          <div class="mt-2">
            <span class="amenity-pill"><i class="fas fa-washer"></i> Washer and dryer</span>
            <span class="amenity-pill"><i class="fas fa-utensils"></i> Essentials (Kitchenware, Bathware, Linens)</span>
            <span class="amenity-pill"><i class="fas fa-snowflake"></i> Air Conditioning</span>
            <span class="amenity-pill"><i class="fas fa-fire"></i> Heating</span>
            <span class="amenity-pill"><i class="fas fa-iron"></i> Full-size ironing board</span>
            <span class="amenity-pill"><i class="fas fa-box-open"></i> Storage</span>
            <span class="amenity-pill"><i class="fas fa-check"></i> Quiet Environment</span>
          </div>
        </div>

        <!-- Description / Read more -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>Overview</h5>
          <p>
            Welcome to New Brunswick! Here we have, ready and waiting for you, a gorgeous and charming brand-new, three-bedroom, family home, ideally located within walking distance of RWJ, Rutgers Cook Douglas Campus, 5 minutes from Walmart, Target, various restaurants, and parks, and with easy access to Route 1 and 130.
          </p>
          <p>
            The property features laminate floors, a modern open plan, and a fully equipped kitchen with vast quartz countert...
            <a href="#" data-bs-toggle="collapse" data-bs-target="#full-desc">Read more</a>
          </p>
          <div id="full-desc" class="collapse">
            <p>
              <!-- Expanded content (sample continuation) -->
              ... countertops, stainless appliances, and ample storage. Bright living spaces, off-street parking, and a private backyard. Ideal for families or professionals seeking a quiet home close to major medical centers and universities.
            </p>
          </div>
        </div>

        <!-- Rooms & beds (anchor we will observe) -->
        <div id="rooms-anchor" class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>Space</h5>
          <p>Can fit 5 people comfortably</p>

          <h6 class="mt-3">Rooms & beds</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="border p-3 mb-2">
                <strong>Bedroom 1</strong>
                <div>Queen Bed</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="border p-3 mb-2">
                <strong>Bedroom 2</strong>
                <div>Queen Bed</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="border p-3 mb-2">
                <strong>Bedroom 3</strong>
                <div>Double (Full) Bed</div>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <strong>1 bathroom</strong>
            <div>Bathroom 1 - Private Bath</div>
          </div>
        </div>

        <!-- Fees & booking summary (in left content but below rooms - optional) -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>Property fees</h5>
          <ul>
            <li>Deposit (Refundable): $1,700</li>
            <li>Cleaning Fee: $150</li>
            <li>Pet Deposit (Non-Refundable): $500</li>
          </ul>
        </div>

        <!-- Map (shows pinpoint from URL or property coords) -->
        <div class="card p-3 mb-4" style="border-radius:.8rem;">
          <h5>Location</h5>
          <div id="property-map"></div>
        </div>

        <!-- Reviews -->
        <div class="card p-3 mb-3" style="border-radius:.8rem;">
          <h5>Reviews</h5>
          <p>Be the first to leave a review for this property after your stay.</p>
          <a href="#" class="btn btn-outline-secondary">Write your review</a>
        </div>

        <!-- About the landlord -->
        <div class="card p-3 mb-5" style="border-radius:.8rem;">
          <h5>About the landlord</h5>
          <div class="d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;font-weight:700;">C</div>
            <div>
              <div class="fw-bold">Cici</div>
              <div class="muted small">Tenure: 2 years, 10 months</div>
              <div class="muted small">New Jersey, NJ</div>
              <div class="mt-2">
                <span class="badge bg-success">Email verified</span>
                <span class="badge bg-success">Phone verified</span>
                <span class="badge bg-success">ID verified</span>
              </div>
            </div>
            <div class="ms-auto">
              <a href="#" class="btn btn-outline-primary btn-sm">Message Landlord</a>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT: Sticky booking card -->
      <div class="col-lg-4">
        <div id="booking-card" class="sticky-card">
          <div class="text-center mb-3">
            <div class="price">$<?php echo number_format($property['monthly_rent'] ?? 3600); ?> <small class="muted">/month</small></div>
            <div class="muted small">Utilities: included</div>
            <div class="muted small">Minimum stay: 1 month</div>
            <div class="muted small">Status: <span class="text-success">Available</span></div>
          </div>

          <hr>

          <div>
            <strong>Property fees</strong>
            <ul class="list-unstyled mt-2">
              <li>Deposit (Refundable): <strong>$1,700</strong></li>
              <li>Cleaning Fee: <strong>$150</strong></li>
              <li>Pet Deposit (Non-Refundable): <strong>$500</strong></li>
            </ul>
          </div>

          <div class="d-grid gap-2 mt-3">
            <button class="btn btn-primary btn-primary-custom">Send Booking Request</button>
            <a href="#" class="btn btn-outline-secondary">Message Landlord</a>
            <a href="tel:<?php echo htmlspecialchars($property['landlord_phone'] ?? ''); ?>" class="btn btn-outline-dark">Call Landlord</a>
            <button class="btn btn-link text-muted">Report This Property</button>
          </div>
        </div>
      </div>
    </div> <!-- /row -->

    <?php endif; /* end if property exists */ ?>

  </div> <!-- /container -->

  <?php include 'footer.php'; ?>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

  <script>
    // Data from PHP
    const property = <?php echo $propertyJson; ?>;
    const images = <?php echo $imagesJson; ?>;
    const urlLat = <?php echo $urlLat !== null ? json_encode($urlLat) : 'null'; ?>;
    const urlLng = <?php echo $urlLng !== null ? json_encode($urlLng) : 'null'; ?>;

    // initialize property map with fallback logic
    (function initPropertyMap() {
      const mapEl = document.getElementById('property-map');
      if (!mapEl) return;

      // default center (New Jersey / US)
      let center = [40.4862, -74.4518];
      let zoom = 12;

      // Use URL lat/lng if provided
      if (urlLat && urlLng) {
        center = [parseFloat(urlLat), parseFloat(urlLng)];
        zoom = 14;
      } else if (property && property.latitude && property.longitude && property.latitude != 0 && property.longitude != 0) {
        center = [parseFloat(property.latitude), parseFloat(property.longitude)];
        zoom = 14;
      } else if (property && property.city) {
        // a minimal city geocode mapping in JS (mirror of PHP)
        const cityCoordinates = {
          'new york': [40.7128, -74.0060],
          'los angeles': [34.0522, -118.2437],
          'chicago': [41.8781, -87.6298],
          'houston': [29.7604, -95.3698],
          'new brunswick': [40.4862, -74.4518]
        };
        const cityKey = property.city.toLowerCase().trim();
        if (cityCoordinates[cityKey]) {
          center = cityCoordinates[cityKey];
          zoom = 13;
        }
      }

      const map = L.map('property-map', { scrollWheelZoom:false }).setView(center, zoom);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // Add a marker at pin location
      const marker = L.marker(center).addTo(map);
      let popupHtml = `<strong>${property && property.property_title ? property.property_title : 'Property'}</strong><br>`;
      if (property && property.monthly_rent) popupHtml += `<small>Price: $${Number(property.monthly_rent).toLocaleString()}/month</small>`;
      marker.bindPopup(popupHtml);

    })();

    // Sticky card stop when Rooms anchor is reached
    (function stickyStopper() {
      const bookingCard = document.getElementById('booking-card');
      const roomsAnchor = document.getElementById('rooms-anchor');

      if (!bookingCard || !roomsAnchor) return;

      // Ensure initial sticky (desktop only)
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            // When the rooms anchor intersects viewport, STOP sticky
            bookingCard.classList.add('stopped');
          } else {
            bookingCard.classList.remove('stopped');
          }
        });
      }, { root: null, threshold: 0.02 });

      observer.observe(roomsAnchor);
    })();

    // Optional: lazy swap right big image when you click small ones (UX nicety)
    (function imagesInteraction() {
      const leftImgs = document.querySelectorAll('.images-left img');
      const rightImg = document.querySelector('.images-right img');
      if (!leftImgs.length || !rightImg) return;
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
