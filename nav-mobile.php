
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Navbar Styles (Desktop) ===== */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            font-family: 'Montserrat', sans-serif;
            background-color: #2b376a;
            transition: background 0.4s ease, box-shadow 0.4s ease;
        }

        .navbar.scrolled {
            background: #2b376a;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar .logo img {
            transform: translateX(-20px);
            max-height: 80px;
            cursor: pointer;
            filter: brightness(0) invert(1);
        }

        .navbar .nav-links {
            display: flex;
            gap: 25px;
            position: relative;
        }

        .navbar .nav-links .nav-item {
            color: #fff;
            text-transform: uppercase;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: color 0.3s ease;
            position: relative;
            padding: 10px 0;
        }

        .navbar:not(.scrolled) .nav-links .nav-item:hover {
            color: #2b376a;
        }

        .navbar.scrolled .nav-links .nav-item:hover {
            color: #c9a86d;
        }

        .navbar .social-icons {
            display: flex;
            gap: 15px;
        }

        .navbar .social-icons a {
            color: #fff;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .navbar .social-icons a:hover {
            color: #c9a86d;
        }

        /* ===== Dropdown Styles ===== */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 4px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #2b376a;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background: #f5f5f5;
            color: #c9a86d;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Add indicator for dropdown items */
        .dropdown > .nav-item::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: 5px;
            font-size: 12px;
        }

        /* ===== Fullscreen Mobile Menu ===== */
        .fullscreen-menu {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            height: 100vh;
            background: #2b376a;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 25px;
            z-index: 1050;
            padding: 40px 20px;
        }

        .fullscreen-menu.show {
            display: flex;
        }

        .fullscreen-menu a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 22px;
            text-align: center;
            transition: color 0.3s ease;
        }

        .fullscreen-menu a:hover {
            color: #c9a86d;
        }

        .fullscreen-menu .menu-header {
            position: absolute;
            top: 15px;
            left: 20px;
            right: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fullscreen-menu .menu-logo img {
            max-height: 60px;
            cursor: pointer;
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }

        .fullscreen-menu .menu-logo img:hover {
            transform: scale(1.05);
        }

        .fullscreen-menu .close-btn {
            position: static; /* reset absolute */
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            color: #fff;
        }

        /* Mobile dropdown styles */
        .mobile-dropdown {
            position: relative;
        }

        .mobile-dropdown-content {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
            padding-left: 20px;
        }

        .mobile-dropdown-content a {
            font-size: 18px;
            color: #e0e0e0;
        }

        .mobile-dropdown.active .mobile-dropdown-content {
            display: flex;
        }

        .mobile-dropdown > a::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: 8px;
            font-size: 14px;
        }

        .mobile-dropdown.active > a::after {
            content: '\f106';
        }

        /* Toggle Buttons */
        .hamburger {
            margin-right: 50px;
            display: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            transform: translateX(75px);
        }

        .close-btn {
            display: none;
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            color: #fff;
            z-index: 1100;
            transform: translateX(15px);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .navbar .nav-links,
            .navbar .social-icons {
                display: none;
            }
            .hamburger {
                display: block;
            }
        }
        /* Hide fullscreen menu completely on desktop */
        @media (min-width: 992px) {
            .fullscreen-menu {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar" id="navbar">
        <!-- Logo -->
        <div class="logo">
            <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
        </div>

        <!-- Desktop Navigation Links -->
        <div class="nav-links">
            <a href="index.php" class="nav-item">Home</a>
            
            <!-- About Us Dropdown -->
            <div class="dropdown">
                <div class="nav-item">About Us</div>
                <div class="dropdown-content">
                    <a href="about-us.php">About Us</a>
                    <a href="meet-the-team.php">Meet the Team</a>
                </div>
            </div>
            
            <!-- Properties Dropdown -->
            <div class="dropdown">
                <div class="nav-item">Properties</div>
                <div class="dropdown-content">
                    <a href="properties.php?type=united-states">United States</a>
                    <a href="properties.php?type=international">International</a>
                </div>
            </div>
            
            <a href="services.php" class="nav-item">Services</a>
            
            <!-- Submit Property Dropdown -->
            <div class="dropdown">
                <div class="nav-item">Submit Property</div>
                <div class="dropdown-content">
                    <a href="submit-property.php">Submit Property</a>
                    <a href="fema.php">FEMA</a>
                    <a href="usace.php">USACE</a>
                </div>
            </div>
            
            <a href="contact.php" class="nav-item">Contact</a>
        </div>

        <!-- Social Icons -->
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>

        <!-- Menu Text (replacing hamburger icon) -->
        <div class="hamburger" id="menuToggle">MENU</div>
    </div>

    <!-- Fullscreen Menu -->
    <div class="fullscreen-menu" id="mobileMenu">
        <div class="menu-header">
            <!-- Logo -->
            <div class="menu-logo">
                <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
            </div>

            <!-- Close Button -->
            <div class="close-btn" id="menuClose">CLOSE</div>
        </div>

        <!-- Menu Links -->
        <a href="index.php">Home</a>
        
        <!-- About Us Mobile Dropdown -->
        <div class="mobile-dropdown">
            <a href="javascript:void(0);">About Us</a>
            <div class="mobile-dropdown-content">
                <a href="about-us.php">About Us</a>
                <a href="meet-the-team.php">Meet the Team</a>
            </div>
        </div>
        
        <!-- Properties Mobile Dropdown -->
        <div class="mobile-dropdown">
            <a href="javascript:void(0);">Properties</a>
            <div class="mobile-dropdown-content">
                <a href="properties.php?type=united-states">United States</a>
                <a href="properties.php?type=international">International</a>
            </div>
        </div>
        
        <a href="services.php">Services</a>
        
        <!-- Submit Property Mobile Dropdown -->
        <div class="mobile-dropdown">
            <a href="javascript:void(0);">Submit Property</a>
            <div class="mobile-dropdown-content">
                <a href="submit-property.php">Submit Property</a>
                <a href="fema.php">FEMA</a>
                <a href="usace.php">USACE</a>
            </div>
        </div>
        
        <a href="contact.php">Contact</a>
    </div>

<script>
    const navbar = document.getElementById('navbar');
    const menu = document.getElementById("mobileMenu");
    const toggle = document.getElementById("menuToggle");
    const closeBtn = document.getElementById("menuClose");

    // ✅ Function to update navbar on scroll
    function updateNavbar() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    // ✅ Listen for scroll (desktop & mobile)
    window.addEventListener('scroll', updateNavbar);
    window.addEventListener('touchmove', updateNavbar, { passive: true });

    // ✅ Mobile Menu Toggle
    toggle.addEventListener("click", () => {
        menu.classList.add("show");
        closeBtn.style.display = "block";
        toggle.style.display = "none";
        document.body.style.overflow = "hidden"; // stop background scroll
        navbar.classList.add("scrolled"); // force background when menu is open
    });

    closeBtn.addEventListener("click", () => {
        menu.classList.remove("show");
        closeBtn.style.display = "none";
        toggle.style.display = "block";
        document.body.style.overflow = "auto"; // allow background scroll again
        updateNavbar(); // reset navbar background
    });

    // ✅ Mobile dropdown functionality
    document.querySelectorAll('.mobile-dropdown > a').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.parentElement;
            dropdown.classList.toggle('active');
        });
    });
</script>
