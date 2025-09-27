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
  background: #00524e;
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
  transition: all 0.3s ease;
  padding: 5px 8px;
  border-radius: 5px;
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
  background: #00524e; /* base background */
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
  transition: all 0.3s ease;
}

.navbar .nav-item.dropdown .dropdown-menu .dropdown-item:hover {
  background: #fff;    /* hover background */
  color: #00524e;      /* hover text */
}

.navbar .nav-item.dropdown:hover .dropdown-menu {
  display: flex;
}

/* Hover Colors */
.navbar:not(.scrolled) .nav-links .nav-item:hover,
.navbar.scrolled .nav-links .nav-item:hover {
  background: #fff;
  color: #00524e;
}

/* Services Dropdown */
.navbar .nav-item.dropdown-services {
  position: relative;
}

.navbar .nav-item.dropdown-services .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: -20px;
  background: #00524e;
  padding: 10px;
  min-width: 360px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 999;
  grid-template-columns: repeat(2, 1fr);
  gap: 0;
}

.navbar .nav-item.dropdown-services:hover .dropdown-menu {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(3, auto);
  gap: 0;
}

.navbar .nav-item.dropdown-services .dropdown-menu .dropdown-item {
  color: #fff;
  padding: 10px 15px;
  font-size: 14px;
  white-space: nowrap;
  transition: all 0.3s ease;
}

.navbar .nav-item.dropdown-services .dropdown-menu .dropdown-item:hover {
  background: #fff;
  color: #00524e;
}

/* Social Icons */
.navbar .social-icons {
  display: flex;
  gap: 15px;
}
.navbar .social-icons a {
  color: #fff;
  font-size: 18px;
  transition: all 0.3s ease;
  padding: 5px;
  border-radius: 50%;
}
.navbar .social-icons a:hover {
  background: #fff;
  color: #0B47A8;
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
    background: #0B47A8;
    padding: 15px 0;
  }
  .nav-item.dropdown .dropdown-menu {
    position: static;
    box-shadow: none;
    background: #0B47A8;
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

/* ===== Header Buttons ===== */
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
}

.btn-list-property {
  background-color: #0B47A8;
  color: white;
  border-radius: 50px;
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
  
  color: #fff;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  border-bottom: 1px solid #f0f0f0;
  background-color: #00524e;

}
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #fff;
  min-width: 200px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  border-radius: 8px; /* ✅ round corners */
  overflow: hidden;   /* keeps the child <a> inside the curve */
  z-index: 1;
}
.dropdown-content a:last-child {
  border-bottom: none;
}

.dropdown-content a:hover {
  background-color: #fff;
  color: #00524e;
}

.dropdown:hover .dropdown-content {
  display: block;
}

/* Inputs inside signup */
#signupModal .modal-content input {
  width: 100%;
  padding: 10px;
  margin: 6px 0;
  border-radius: 6px;
  border: 1px solid #ddd;
  font-size: 14px;
}

/* Button */
#signupModal .modal-content button {
  width: 100%;
  padding: 10px;
  margin-top: 14px;
  border: none;
  background: #00524e;
  color: white;
  font-size: 16px;
  border-radius: 6px;
  cursor: pointer;
}
#signupModal .modal-content button:hover {
  background: #0B47A8;
}
/* Medium size modal */
#signupModal .modal-content,
#loginModal .modal-content {
  width: 500px;        /* medium width */
  max-width: 90%;      /* stay responsive on small screens */
  margin: auto;
  padding: 20px;
  border-radius: 10px;
}
/* First + Last name on the same row */
#signupModal .name-row {
  display: flex;
  gap: 10px;
}

#signupModal .name-row input {
  flex: 1; /* equal width */
}


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
  <a href="#" onclick="openModal('loginModal')">Log in</a>
  <a href="#" onclick="openModal('signupModal')">Create account</a>
</div>

  </div>
</div>
</div>
<!-- Login Modal -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('loginModal')">&times;</span>
    <h2>Log In</h2>

    <!-- Step 1: Email -->
    <form id="loginStep1">
      <input type="email" id="loginEmail" placeholder="Email" required>
      <button type="button" onclick="goToPasswordStep()">Next</button>
    </form>

    <!-- Step 2: Password (hidden by default) -->
    <form id="loginStep2" style="display:none;">
      <p id="showEmail" style="font-weight: 600; margin-bottom: 10px; color:#00524e;"></p>
      <input type="hidden" id="finalEmail" name="email"> <!-- hidden field for form submission -->
      <input type="password" id="loginPassword" placeholder="Password" required>
      
      <button type="submit">Log In</button>
      <button type="button" onclick="backToEmailStep()" style="margin-top:10px;background:#ccc;color:#000;">Back</button>
    </form>

    <p>Don’t have an account? 
      <a href="#" onclick="switchModal('loginModal', 'signupModal')">Sign up</a>
    </p>
  </div>
</div>


<!-- Signup Modal -->
<div id="signupModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('signupModal')">&times;</span>
    <h2>Create a tenant account</h2>

<form>
<style>
  .form-group {
    position: relative;
  }

  .form-group input {
    width: 100%;
    padding: 12px 10px;
    font-size: 16px;
    border: 1px solid #aaa;
    border-radius: 6px;
    outline: none;
    background: none;
  }

  .form-group label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #777;
    pointer-events: none;
    transition: 0.3s ease;
    background: #fff;
    padding: 0 4px;
  }

  /* When input is focused or not empty */
  .form-group input:focus + label,
  .form-group input:not(:placeholder-shown) + label {
    top: 7px;
    left: 8px;
    font-size: 12px;
  }
</style>

<div class="name-row" style="display:flex;">
  <div class="form-group" style="flex:1;">
    <input type="text" placeholder=" " required>
    <label>First Name</label>
  </div>
  <div class="form-group" style="flex:1;">
    <input type="text" placeholder=" " required>
    <label>Last Name</label>
  </div>
</div>

<div class="form-group">
  <input type="email" placeholder=" " required>
  <label>Email</label>
</div>

<div class="form-group">
  <input type="tel" placeholder=" " required>
  <label>Phone</label>
</div>

<label style="display:flex; align-items:center; cursor:pointer; width: 100%; margin-top:20px;">
  <input type="checkbox" style=" transform:translateX(-150px); margin-right: -350px;" required>
  <span>
    I have read and agree to 
    <a href="terms.php" target="_blank" style="color:#00524e;text-decoration:underline;">
      Terms of Use
    </a>
  </span>
</label>



  <button type="submit">Create Account</button>
</form>


    <p style="margin-top:15px;font-size:14px; transform: translateY(15px);">
      Looking to create a landlord account?  
      <a href="list-property.php" style="color:#00524e;font-weight:600;">List Your Property</a>
    </p>

    <p style="margin-top:10px;font-size:14px;">
      Already have an account?  
      <a href="#" onclick="switchModal('signupModal', 'loginModal')" style="color:#0B47A8;font-weight:600;">Log in</a>
    </p>
  </div>
</div>


<style>
  /* Modal Background */
.modal {
  display: none; 
  position: fixed; 
  z-index: 2000; 
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center; align-items: center;
}

/* Modal Box */
.modal-content {
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  width: 350px;
  text-align: center;
  animation: fadeIn 0.3s ease-in-out;
}

/* Inputs */
.modal-content input {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border-radius: 6px;
  border: 1px solid #ddd;
}

/* Buttons */
.modal-content button {
  width: 100%;
  padding: 10px;
  margin-top: 12px;
  border: none;
  background: #00524e;
  color: white;
  font-size: 16px;
  border-radius: 6px;
  cursor: pointer;
}
.modal-content button:hover {
  background: #0B47A8;
}

/* Close Button */
.modal-content .close {
  position: absolute;
  right: 15px; top: 10px;
  font-size: 22px;
  cursor: pointer;
}

@keyframes fadeIn {
  from {opacity: 0; transform: scale(0.9);}
  to {opacity: 1; transform: scale(1);}
}

</style>

<!-- Add Font Awesome for the icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
  // Open Modal
function openModal(id) {
  document.getElementById(id).style.display = "flex";
}

// Close Modal
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// Switch Modal
function switchModal(current, target) {
  closeModal(current);
  openModal(target);
}

// Close modal when clicking outside
window.onclick = function(event) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
};

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
  window.addEventListener("scroll", function () {
  const btn = document.querySelector(".btn-list-property");

  if (window.scrollY > 0) {
    btn.classList.add("scrolled");
  } else {
    btn.classList.remove("scrolled");
  }
});

</script>
<script>
function goToPasswordStep() {
  const email = document.getElementById("loginEmail").value;
  if (email.trim() === "") {
    alert("Please enter your email first.");
    return;
  }

  // Show email on password step
  document.getElementById("showEmail").textContent = email;
  document.getElementById("finalEmail").value = email;

  // Switch steps
  document.getElementById("loginStep1").style.display = "none";
  document.getElementById("loginStep2").style.display = "block";
}

function backToEmailStep() {
  // Switch back
  document.getElementById("loginStep1").style.display = "block";
  document.getElementById("loginStep2").style.display = "none";
}
</script>
