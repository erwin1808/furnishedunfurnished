
<!-- Footer -->
<footer>
    <div class="content-container">
        <div class="footer-container">
            
          
<!-- Column 1 -->
<div class="footer-col">
    <h3>Streamlined Stays</h3>
    <ul>
        <li><i class="fas fa-phone"></i> <a href="tel:+18778732699" style="text-decoration: none; transform:translateY(4px)">+(877) 873-2699</a></li>

        <li><i class="fas fa-envelope"></i> <a href="mailto:housing@streamlinedstays.com/" style="text-decoration: none; transform:translateY(4px)">housing@streamlinedstays.com</a></li>
    </ul>
</div>


<!-- Logos Section -->
<div class="footer-logos">
    <img src="images/corporate_housing_providers_association_RGB.png" alt="Logo 1" class="footer-logo">
    <img src="images/Service-Disabled-Veteran-Owned-Certified-2-1.png" alt="Logo 2" class="footer-logo">
</div>


<style>
.footer-logos {
    display: flex;
    justify-content: center; /* center horizontally */
    gap: 30px; /* space between logos */
    margin-top: -50px; /* adjust vertical alignment */
}

.footer-logo {
    width: 300px; /* exact logo width */
    height: 200px; /* exact logo height */
    object-fit: contain; /* maintain aspect ratio without distortion */
}

/* Responsive for mobile */
@media (max-width: 768px) {
    .footer-logos {
        flex-direction: row;
        justify-content: center;
        gap: 20px;
        margin-top: 0;
    }

    .footer-logo {
        width: 150px; /* slightly smaller on mobile */
        height: 100px;
    }
}

</style>


<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var lat = 35.0484;
        var lng = -80.8173;

        // Initialize map
        var map = L.map('map').setView([lat, lng], 15);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Google Maps directions link
        var directionsLink = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

        // Add marker with popup containing Get Directions link
        L.marker([lat, lng])
            .addTo(map)
            .bindPopup(`
                <b>12773 Tom Short Rd</b><br>
                Charlotte, NC 28277<br>
                <a href="${directionsLink}" target="_blank" style="color:#0B47A8; text-decoration:underline;">
                    Get Directions
                </a>
            `)
            .openPopup();
    });
</script>




        </div>

        <!-- Copyright -->
  <div class="footer-bottom">
    <div class="copyright">
        <p style="font-family: 'Montserrat', sans-serif;">&copy; 2025 Streamlined Stays Group. All Rights Reserved.</p>
    </div>
  <div class="social-links">
<a href="https://www.instagram.com/streamlined.stays/" target="_blank"><i class="fab fa-instagram"></i></a>
<a href="https://www.linkedin.com/company/streamlined-stay-solutions/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
</div>

</div>

<style>

        /* Footer */
        footer {
            background:#2b376a;
            color: var(--light);
            padding: 80px 0 30px;
            position: relative;
            
        }
        
     /* Default footer style (all pages) */
        /* Default footer style (all pages) */
.footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 20px; /* reduced bottom space */
    margin-top: -180px; /* default, you can tweak */
}



        .footer-col h3 {
            margin-left: 30px;
            letter-spacing: 1px;
            font-family: 'Commuter Sans', sans-serif;
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
            color: var(--light);
            text-transform: uppercase;
        }
        
        .footer-col h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }
        
        .footer-col p {
            color: var(--light-gray);
            line-height: 1.1;
            margin-bottom: 10px;
            
        }
        
        .footer-col ul li {
            display: flex;
            align-items: center;
            gap: 10px; /* spacing between icon and text */
            font-family: 'Montserrat', sans-serif;
            color: var(--light-gray);
            font-size: 16px;
        }
    .footer-col ul li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Montserrat', sans-serif;
        color: var(--light-gray);
        margin-bottom: 10px; /* space between rows */
    }

.footer-col ul li a {
    position: relative;
    color: var(--light-gray);
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    margin-bottom: 4px;
    padding-bottom: 2px; /* space for line */
    transition: color 0.3s ease;
}

.footer-col ul li a::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    height: 2px;
    width: 0;
    background: white;
    transition: width 0.3s ease;
}

.footer-col ul li a:hover {
    color: white;
}

.footer-col ul li a:hover::after {
    width: 100%;
}



        
        .footer-col ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            color: var(--light);
            transition: all 0.3s ease;
            
        }
        
        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }
      
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: var(--accent-dark);
            transform: translateY(-3px);
        }
        
.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-family: 'Montserrat', sans-serif; /* Applies to all text inside footer-bottom */
    color: var(--light-gray);
}

.footer-bottom .copyright p {
    margin: 0;
    font-size: 14px;
}

.footer-bottom .social-links a {
    color: #fff;
    margin-left: 15px;
    font-size: 18px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-bottom .social-links a:hover {
    color: #0B47A8;
}

/* Fixed footer only on mobile */
@media (max-width: 768px) {
  footer {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 999;
    padding: 15px 10px; /* tighter spacing for small screens */
  }

  .footer-container {
    grid-template-columns: 1fr; /* stack columns on mobile */
    gap: 20px;
    margin-top: -175    px;
  }

  #map {
    width: 100% !important; /* Make map fit screen */
    margin: 0 auto;
  }

  .footer-bottom {
    flex-direction: column;
    gap: 10px;
    text-align: center;
  }

  .social-links {
    justify-content: center;
  }
}

</style>


    </div>
</footer>


    <!-- Back to Top Button -->
    <div class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
<script>
document.addEventListener("scroll", function () {
    const footer = document.querySelector("footer");
    const scrollPosition = window.scrollY + window.innerHeight;
    const pageHeight = document.documentElement.scrollHeight;

    if (scrollPosition >= pageHeight - 10) {
        footer.classList.add("show-footer");
    } else {
        footer.classList.remove("show-footer");
    }
});
</script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


    