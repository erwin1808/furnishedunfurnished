<!-- Meta viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
  background: transparent;
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
}

.navbar .nav-links .nav-item {
  color: #fff;
  text-transform: uppercase;
  font-weight: 500;
  font-size: 15px;
  cursor: pointer;
  transition: color 0.3s ease;
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
</style>

<!-- Navbar -->
<div class="navbar" id="navbar">
  <!-- Logo -->
  <div class="logo">
    <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
  </div>

  <!-- Menu Text (replacing hamburger icon) -->
  <div class="hamburger" id="menuToggle">MENU</div>
</div>

<!-- Fullscreen Menu -->
<div class="fullscreen-menu" id="mobileMenu">
  <div class="close-btn" id="menuClose">CLOSE</div>
  <a href="index.php">Home</a>
  <a href="about-us.php">About Us</a>
  <a href="properties.php">Properties</a>
  <a href="services.php">Services</a>
  <a href="submit-property.php">Submit Property</a>
  <a href="contact.php">Contact</a>
</div>

<script>
// Change background on scroll
window.addEventListener('scroll', function() {
  const navbar = document.getElementById('navbar');
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// Mobile Menu Toggle
const menu = document.getElementById("mobileMenu");
const toggle = document.getElementById("menuToggle");
const closeBtn = document.getElementById("menuClose");

toggle.addEventListener("click", () => {
  menu.classList.add("show");
  closeBtn.style.display = "block";
  toggle.style.display = "none";
});

closeBtn.addEventListener("click", () => {
  menu.classList.remove("show");
  closeBtn.style.display = "none";
  toggle.style.display = "block";
});
</script>
