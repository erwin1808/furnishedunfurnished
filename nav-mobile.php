<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ===== Navbar Styles ===== */
.navmobile {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    font-family: 'Montserrat', sans-serif;
    background-color: transparent;
    transition: background 0.4s ease, box-shadow 0.4s ease;
}

.navmobile.scrolled {
    background: #2b376a;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.navmobile .navmobile-logo img {
    transform: translateX(-20px);
    max-height: 80px;
    cursor: pointer;
    filter: brightness(0) invert(1);
}

.navmobile .navmobile-links {
    display: flex;
    gap: 25px;
    position: relative;
}

.navmobile .navmobile-links .navmobile-item {
    color: #fff;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
    transition: color 0.3s ease;
    position: relative;
    padding: 10px 0;
}

.navmobile:not(.scrolled) .navmobile-links .navmobile-item:hover {
    color: #2b376a;
}

.navmobile.scrolled .navmobile-links .navmobile-item:hover {
    color: #c9a86d;
}

.navmobile .navmobile-social {
    display: flex;
    gap: 15px;
}

.navmobile .navmobile-social a {
    color: #fff;
    font-size: 18px;
    transition: color 0.3s ease;
}

.navmobile .navmobile-social a:hover {
    color: #c9a86d;
}

/* Dropdown */
.navmobile-dropdown {
    position: relative;
}

.navmobile-dropdown-content {
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

.navmobile-dropdown-content a {
    color: #2b376a;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-size: 14px;
    transition: background 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}

.navmobile-dropdown-content a:last-child {
    border-bottom: none;
}

.navmobile-dropdown-content a:hover {
    background: #f5f5f5;
    color: #c9a86d;
}

.navmobile-dropdown:hover .navmobile-dropdown-content {
    display: block;
}

.navmobile-dropdown > .navmobile-item::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-left: 5px;
    font-size: 12px;
}

/* Mobile Fullscreen Menu */
.navmobile-menu {
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

.navmobile-menu.show {
    display: flex;
}

.navmobile-menu a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    font-size: 22px;
    text-align: center;
    transition: color 0.3s ease;
}

.navmobile-menu a:hover {
    color: #c9a86d;
}

.navmobile-menu-header {
    position: absolute;
    top: 15px;
    left: 20px;
    right: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navmobile-menu-logo img {
    max-height: 60px;
    cursor: pointer;
    filter: brightness(0) invert(1);
    transition: transform 0.3s ease;
}

.navmobile-menu-logo img:hover {
    transform: scale(1.05);
}

.navmobile-menu-close {
    position: static;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    color: #fff;
}

/* Mobile dropdown */
.navmobile-mobile-dropdown {
    position: relative;
}

.navmobile-mobile-dropdown-content {
    display: none;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-top: 10px;
    padding-left: 20px;
}

.navmobile-mobile-dropdown-content a {
    font-size: 18px;
    color: #e0e0e0;
}

.navmobile-mobile-dropdown.active .navmobile-mobile-dropdown-content {
    display: flex;
}

.navmobile-mobile-dropdown > a::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-left: 8px;
    font-size: 14px;
}

.navmobile-mobile-dropdown.active > a::after {
    content: '\f106';
}

/* Hamburger */
.navmobile-hamburger {
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

/* Overlay Text for Mobile */
@media (max-width: 991px) {
    .navmobile-hamburger {
        display: block;
    }
    .navmobile-overlay-text {
        position: absolute;
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        color: var(--light);
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        background: #716e65;
        padding: 6px 12px;
        border-radius: 50px;
        opacity: 0; /* hidden by default */
        transition: opacity 0.3s ease;
    }
}

@media (min-width: 992px) {
    .navmobile-menu {
        display: none !important;
    }
}
</style>

<body>
<!-- Desktop Navbar -->
<div class="navmobile" id="navmobile">
    <div class="navmobile-logo">
        <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
    </div>

 

    <div class="navmobile-hamburger" id="menuToggle">MENU</div>
</div>

<!-- Mobile Fullscreen Menu -->
<div class="navmobile-menu" id="mobileMenu">
    <div class="navmobile-menu-header">
        <div class="navmobile-menu-logo">
            <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
        </div>
        <div class="navmobile-menu-close" id="menuClose">CLOSE</div>
    </div>

    <a href="index.php">Home</a>

    <div class="navmobile-mobile-dropdown">
        <a href="javascript:void(0);">About Us</a>
        <div class="navmobile-mobile-dropdown-content">
            <a href="about-us.php">About Us</a>
            <a href="meet-the-team.php">Meet the Team</a>
        </div>
    </div>

    <div class="navmobile-mobile-dropdown">
        <a href="javascript:void(0);">Properties</a>
        <div class="navmobile-mobile-dropdown-content">
            <a href="properties.php?type=united-states">United States</a>
            <a href="properties.php?type=international">International</a>
        </div>
    </div>

    <a href="services.php">Services</a>

    <div class="navmobile-mobile-dropdown">
        <a href="javascript:void(0);">Submit Property</a>
        <div class="navmobile-mobile-dropdown-content">
            <a href="submit-property.php">Submit Property</a>
            <a href="fema.php">FEMA</a>
            <a href="usace.php">USACE</a>
        </div>
    </div>

    <a href="contact.php">Contact</a>
</div>

<script>
const navmobile = document.getElementById('navmobile');
const menu = document.getElementById('mobileMenu');
const toggle = document.getElementById('menuToggle');
const closeBtn = document.getElementById('menuClose');

function updateNavmobile() {
    if(window.scrollY > 50) {
        navmobile.classList.add('scrolled');
    } else {
        navmobile.classList.remove('scrolled');
    }
}

window.addEventListener('scroll', updateNavmobile);
window.addEventListener('touchmove', updateNavmobile, { passive: true });

toggle.addEventListener('click', () => {
    menu.classList.add('show');
    closeBtn.style.display = 'block';
    toggle.style.display = 'none';
    document.body.style.overflow = 'hidden';
    navmobile.classList.add('scrolled');
});

closeBtn.addEventListener('click', () => {
    menu.classList.remove('show');
    closeBtn.style.display = 'none';
    toggle.style.display = 'block';
    document.body.style.overflow = 'auto';
    updateNavmobile();
});

document.querySelectorAll('.navmobile-mobile-dropdown > a').forEach(item => {
    item.addEventListener('click', function(e){
        e.preventDefault();
        const dropdown = this.parentElement;
        dropdown.classList.toggle('active');
    });
});
</script>
