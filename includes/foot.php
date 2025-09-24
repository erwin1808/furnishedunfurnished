<!-- foot.php -->
<style>

    /* Footer */
    footer {
        background-color: #1a252f;
        color: white;
        padding: 50px 0 20px;
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .footer-section {
        flex: 1;
        min-width: 250px;
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .footer-section h3 {
        margin-bottom: 20px;
        font-size: 1.2rem;
        color: #e67e22;
    }

    .footer-section p, .footer-section a {
        color: #ecf0f1;
        margin-bottom: 10px;
        display: block;
        text-decoration: none;
    }

    .footer-section a:hover {
        color: #e67e22;
    }

    .social-links a {
        display: inline-block;
        margin-right: 15px;
        color: #ecf0f1;
        font-size: 1.2rem;
        transition: color 0.3s;
    }

    .social-links a:hover {
        color: #e67e22;
    }

    .copyright {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #34495e;
        font-size: 0.9rem;
        color: #bdc3c7;
    }

    #map {
        height: 250px;
        width: 40%;
    }

    @media (max-width: 768px) {
        #map {
            height: 150px;
            width: 100%;
        }
    }

    .leaflet-control-attribution {
        font-size: 11px;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Footer -->
<footer id="contact">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Ohio Dental Repair</h3>
                <p>1967 Lockbourne Road Suite B</p>
                <p>Columbus, Ohio 43207</p>
                <!--p><i class="fas fa-phone"></i> (614) 306-9986</p-->
                <p><i class="fas fa-envelope"></i> ohiodentalrepair@gmail.com</p>
                <a href="https://www.facebook.com/" target="_blank">
                    <i class="fab fa-facebook-f"></i> Follow Us
                </a>
            </div>

    

            <div id="map"></div>
        </div>

        <div class="copyright">
            <p>&copy; 2014 DJD - Ohio Dental Repair. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
  // Target coordinates for Columbus, Ohio address (approximate)
  const targetLat = 39.9251218591576;
  const targetLng = -82.96556042674005; // Fixed the double minus

  // Create map
  const map = L.map('map').setView([targetLat, targetLng], 16);

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Add marker with clickable link
  const marker = L.marker([targetLat, targetLng]).addTo(map);
  marker.bindPopup(`<a href="https://www.google.com/maps/dir/?api=1&destination=${targetLat},${targetLng}" target="_blank">Get Directions</a>`);
</script>

