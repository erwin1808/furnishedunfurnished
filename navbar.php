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
  background: #243065;
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
  margin-left: 100px;
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
  left: -20px;
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
.navbar .nav-item.dropdown-services {
  position: relative;
}

/* Initial hidden state */
.navbar .nav-item.dropdown-services .dropdown-menu {
  display: none; /* hidden by default */
  position: absolute;
  top: 100%;
  left: -20px;
  background: #2b376a;
  padding: 10px;
  min-width: 360px; /* wide enough for 2 columns */
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 999;

  /* Grid only applied on hover */
}

/* Hover state */
.navbar .nav-item.dropdown-services:hover .dropdown-menu {
  display: grid; /* show as grid */
  grid-template-columns: repeat(2, 1fr); /* 2 columns */
  grid-template-rows: repeat(3, auto); /* 3 rows */
  gap: 0; /* space between items */
}

.navbar .nav-item.dropdown-services .dropdown-menu .dropdown-item {
  color: #fff;
  padding: 10px 15px;
  font-size: 14px;
  white-space: nowrap;
  transition: background 0.3s ease;
}

.navbar .nav-item.dropdown-services .dropdown-menu .dropdown-item:hover {
  background: #c9a86d;
  color: #2b376a;
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


    <!-- Properties Dropdown -->
    <div class="nav-item dropdown">
      Properties
      <div class="dropdown-menu">
        <div class="dropdown-item" onclick="window.location.href='properties-us.php'">United States</div>
        <div class="dropdown-item" onclick="window.location.href='properties-international.php'">International</div>
      </div>
    </div>


<div class="nav-item dropdown-services">
  Services
  <div class="dropdown-menu">
    <div class="dropdown-item" onclick="window.location.href='insurance-relocation.php'">Insurance Relocation</div>
    <div class="dropdown-item" onclick="window.location.href='midterm-rentals.php'">Midterm Rentals</div>
    <div class="dropdown-item" onclick="window.location.href='corporate-housing.php'">Corporate Housing</div>
    <div class="dropdown-item" onclick="window.location.href='government-lodging.php'">Government Lodging</div>
    <div class="dropdown-item" onclick="window.location.href='emergency-lodging.php'">Emergency Lodging</div>
    <div class="dropdown-item" onclick="window.location.href='business-travel.php'">Business Travel</div>
  </div>
</div>

 


   <!-- About Us Dropdown -->
    <div class="nav-item dropdown">
      About Us
      <div class="dropdown-menu">
        <div class="dropdown-item" onclick="window.location.href='about-us.php'">About Us</div>
        <div class="dropdown-item" onclick="window.location.href='meet-the-team.php'">Meet the Team</div>
      </div>
    </div>
   
    <div class="nav-item" onclick="window.location.href='contact.php'">Contact</div>
  </div>

<div class="header-buttons">
  <button class="btn-list-property" onclick="location.href='#list-property'">
    List your property
  </button>
  
  <div class="dropdown">
    <button class="btn-login-dropdown">
      <i class="fas fa-user-circle"></i>
    </button>
    <div class="dropdown-content">
      <a href="#login">Log in</a>
      <a href="#signup">Create account</a>
    </div>
  </div>
</div>
</div>
<style>
.header-buttons {
  display: flex;
  gap: 15px;
  align-items: center;
}

.btn-list-property, .btn-login-dropdown {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 400;
  transition: all 0.3s ease;
}

.btn-list-property {
  background-color: #0B47A8;
  color: white;
  border-radius: 50px;
}

.btn-list-property:hover {
  background-color: transparent;
  
}

.dropdown {
  position: relative;
  display: inline-block;
}

.btn-login-dropdown {
  background-color: #f8f9fa;
  color: #0B47A8;
  border: 1px solid #ddd;
  padding: 5px  5.5px 2.5px 5.5px;
  border-radius: 50%;
}

.btn-login-dropdown:hover {
  background-color: #e9ecef;
}

.btn-login-dropdown i {
  font-size: 32px;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: white;
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
  border-radius: 5px;
  z-index: 1;
}

.dropdown-content a {
  color: #333;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  border-bottom: 1px solid #f0f0f0;
}

.dropdown-content a:last-child {
  border-bottom: none;
}

.dropdown-content a:hover {
  background-color: #f8f9fa;
}

.dropdown:hover .dropdown-content {
  display: block;
}
</style>

<!-- Add Font Awesome for the icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
