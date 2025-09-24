<!-- Meta viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ===== Navbar Styles ===== */
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

/* Logo */
.navbar .logo img {
  max-height: 100px;
  cursor: pointer;
  filter: brightness(0) invert(1);
}

/* Nav Links */
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
  position: relative;
  transition: color 0.3s ease;
}

/* Dropdown Menu */
.navbar .nav-item.dropdown {
  position: relative;
}

.navbar .nav-item.dropdown .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background: #2b376a;
  padding: 10px 0;
  min-width: 180px;
  flex-direction: column;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 999;
}

.navbar .nav-item.dropdown .dropdown-menu .dropdown-item {
  color: #fff;
  padding: 10px 20px;
  font-size: 14px;
  white-space: nowrap;
  transition: background 0.3s ease;
}

.navbar .nav-item.dropdown .dropdown-menu .dropdown-item:hover {
  background: #c9a86d;
  color: #2b376a;
}

.navbar .nav-item.dropdown:hover .dropdown-menu {
  display: flex;
}

/* Hover Colors */
.navbar:not(.scrolled) .nav-links .nav-item:hover {
  color: #2b376a;
}
.navbar.scrolled .nav-links .nav-item:hover {
  color: #c9a86d;
}

/* Social Icons */
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

/* Responsive */
@media (max-width: 991px) {
  .navbar {
    flex-wrap: wrap;
    padding: 15px 20px;
  }
  .nav-links {
    display: none;
    flex-direction: column;
    width: 100%;
    text-align: center;
    margin-top: 10px;
  }
  .nav-links.active {
    display: flex;
    background: #2b376a;
    padding: 15px 0;
  }
  .nav-item.dropdown .dropdown-menu {
    position: static;
    box-shadow: none;
    background: #2b376a;
    display: none;
    flex-direction: column;
    width: 100%;
  }
  .nav-item.dropdown.active .dropdown-menu {
    display: flex;
  }
  .hamburger {
    display: block;
    cursor: pointer;
    font-size: 24px;
    color: #fff;
    margin-left: auto;
  }
}
.hamburger { display: none; }
</style>

<!-- Navbar -->
<div class="navbar" id="navbar">
  <!-- Logo -->
  <div class="logo">
    <img src="images/output-onlinepngtools-3-60x40.png" alt="Logo" onclick="window.location.href='index.php';">
  </div>

  <!-- Hamburger for mobile -->
  <div class="hamburger" id="hamburgerMenu">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Nav Links -->
  <div class="nav-links" id="navLinks">
    <div class="nav-item" onclick="window.location.href='index.php'">Home</div>

    <!-- About Us Dropdown -->
    <div class="nav-item dropdown">
      About Us
      <div class="dropdown-menu">
        <div class="dropdown-item" onclick="window.location.href='about-us.php'">About Us</div>
        <div class="dropdown-item" onclick="window.location.href='meet-the-team.php'">Meet the Team</div>
      </div>
    </div>

    <!-- Properties Dropdown -->
    <div class="nav-item dropdown">
      Properties
      <div class="dropdown-menu">
        <div class="dropdown-item" onclick="window.location.href='properties-us.php'">United States</div>
        <div class="dropdown-item" onclick="window.location.href='properties-international.php'">International</div>
      </div>
    </div>

    <!-- Submit Property Dropdown -->
    <div class="nav-item dropdown">
      Submit Property
      <div class="dropdown-menu">
        <div class="dropdown-item" onclick="window.location.href='submit-property.php'">Submit Property</div>
        <div class="dropdown-item" onclick="window.location.href='fema.php'">FEMA</div>
        <div class="dropdown-item" onclick="window.location.href='usace.php'">USACE</div>
      </div>
    </div>

    <div class="nav-item" onclick="window.location.href='services.php'">Services</div>
    <div class="nav-item" onclick="window.location.href='contact.php'">Contact</div>
  </div>

  <!-- Social Icons -->
  <div class="social-icons">
    <a href="#"><i class="fab fa-facebook-f"></i></a>
    <a href="#"><i class="fab fa-twitter"></i></a>
    <a href="#"><i class="fab fa-linkedin-in"></i></a>
  </div>
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

  // Mobile menu toggle
  const hamburger = document.getElementById('hamburgerMenu');
  const navLinks = document.getElementById('navLinks');

  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    const icon = hamburger.querySelector('i');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
  });

  // Mobile dropdown toggle
  const dropdowns = document.querySelectorAll('.nav-item.dropdown');
  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('click', () => {
      if (window.innerWidth <= 991) {
        dropdown.classList.toggle('active');
      }
    });
  });
</script>
