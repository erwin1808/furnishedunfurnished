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
    background: #00524e;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.navmobile .navmobile-logo img {
    transform: translateX(-30px);
    max-height: 80px;
    cursor: pointer;
    filter: brightness(0) invert(1);
}

.navmobile .navmobile-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.navmobile .btn-submit-property {
    transform: translateX(30px);
    padding: 10px 10px;
    background-color: #0B47A8;
    color: #fff;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 14px;
}

.navmobile .btn-submit-property:hover {
    background: transparent;
    color: #0B47A8;
    border: 1px solid #0B47A8;
}

/* Hamburger */
.navmobile-hamburger {
    transform: translateX(20px);
    display: none;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Mobile Fullscreen Menu */
.navmobile-menu {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    height: 100vh;
    background: #00524e;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 80px 20px 40px 20px;
}

.navmobile-menu.show {
    display: flex;
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
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    color: #fff;
}

/* Horizontal nav links row */
.navmobile-menu-links {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
    width: 100%;
    font-family: 'Courier New', Courier, monospace;
}

.navmobile-menu-links a {
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    font-size: 22px;
    text-align: center;
    transition: color 0.3s ease;
}

.navmobile-menu-links a:hover {
    color: #c9a86d;
}

/* Dropdown styling */
.navmobile-mobile-dropdown {
    width: 100%;
    text-align: center;
    position: relative;
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

.navmobile-mobile-dropdown-content {
    display: none;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.navmobile-mobile-dropdown-content a {
    color: #e0e0e0;
    font-size: 18px;
}

.navmobile-mobile-dropdown.active .navmobile-mobile-dropdown-content {
    display: flex;
}
/* Auth buttons (Login & Register) */
.navmobile-auth {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 50px;
}

.navmobile-auth a {
    background: #2b376a;
    color: #fff;
    padding: 10px 25px;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.navmobile-auth a:hover {
    background: transparent;
    border: 1px solid #0B47A8;
    color: #0B47A8;
}

/* Responsive */
@media (max-width: 991px) {
    .navmobile-hamburger {
        display: block;
    }
}
</style>

<body>
<!-- Navbar -->
<div class="navmobile" id="navmobile">
    <div class="navmobile-left">
        <div class="navmobile-logo">
            <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
        </div>
        <button class="btn-submit-property" onclick="window.location.href='submit-property.php'">List Your Property</button>
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

    <!-- Nav Links Row -->
  <div class="navmobile-menu-links">
    <a href="request-housing.php">Request Housing</a>

    <div class="navmobile-mobile-dropdown">
        <a href="javascript:void(0);">Resources</a>
        <div class="navmobile-mobile-dropdown-content">
            <a href="media.php">Media</a>
            <a href="faqs.php">FAQs</a>
            <a href="blogs.php">Blogs</a>
        </div>
    </div>

    <a href="about-us.php">About Us</a>
</div>

    <!-- Auth Buttons -->
    <div class="navmobile-auth">
        <a href="#login">Login</a>
        <a href="#signup">Register</a>
    </div>
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
