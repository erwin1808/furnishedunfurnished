<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3S</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        /* Updated Color Scheme for Construction/Engineering */
        :root {
            --primary: #1873ba;  /* Vibrant blue */
            --primary-dark: #115283ff;
            --secondary: #0B47A8; /* Amber */
            --secondary-dark: #0B47A8;
            --dark: #111827;     /* Deep gray */
            --darker: #0d1321;
            --light: #faf9f5;    /* Off-white */
            --gray: #6b7280;
            --light-gray: #e5e7eb;
            --success: #9ccc65;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Make map tiles black and white while keeping markers/popups colored */
        .leaflet-tile-container img {
            filter: grayscale(100%) contrast(110%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Commuters Sans Font */
        @font-face {
            font-family: "Commuters Sans";
            src: url("assets/fonts/Demo_Fonts/Fontspring-DEMO-commuterssans-regular.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
        }
        
        /* Add other font-face declarations as needed */
        
        body {
            font-family: "Commuters Sans", sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
            scroll-behavior: smooth;
        }
        
        a {
            text-decoration: none;
            color: var(--primary);
        }
        
        ul {
            list-style: none;
        }
        
        .content-container {
            max-width: 1400px;
            margin: 200px auto 0;
            padding: 0 30px;
        }
        
        /* Modern buttons */
        .btn-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: var(--accent);
            color: var(--dark);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-secondary-custom {
            background: var(--secondary);
        }
        
        .btn-secondary-custom:hover {
            background: var(--secondary-dark);
        }
        
        .btn-custom i {
            margin-right: 8px;
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .section-title {
            font-size: 2.6rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            color: #1b75bb;
            font-weight: 800;
            text-align: center;
            text-transform: capitalize;
            font-family: 'Commuters Sans', sans-serif;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--dark);
            max-width: 1200px;
            margin: -30px auto 80px;
            text-align: center;
            line-height: 1.7;
            font-family: 'Montserrat', sans-serif;
        }

        .hero {
  position: relative;
  background: url('images/dsc_3531.jpg') no-repeat center center/cover;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

/* Overlay */
.hero-overlay {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.16);
  z-index: 1;
}

/* Hero content */
.hero-content {
  position: relative;
  z-index: 2;
  max-width: 700px;
  padding: 20px;
  text-align: left;
  color: var(--light);
  animation: fadeInUp 1s ease forwards;

  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* Headings */
.hero-content h1,
.hero-title {
  text-transform: uppercase;
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: 15px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

/* Mobile-first style */
.hero-title {
  font-size: 2rem;  /* smaller for phones */
  width: 100%;      /* no fixed width */
  white-space: normal; /* allow wrapping */
}

/* Tablets */
@media (min-width: 768px) {
    .hero{
        height: 30vh;
    }
  .hero-title {
    font-size: 2.5rem;
  }

}

/* Large desktops */
@media (min-width: 1200px) {
  .hero-title {
    font-size: 3.5rem;
  }
}

/* Paragraphs */
.hero-content p {
  font-size: 1rem;
  margin-bottom: 20px;
  font-weight: 500;
  text-shadow: 0 2px 6px rgba(0,0,0,0.4);
}

/* Buttons wrapper */
.hero-btns {
  display: flex;
  justify-content: flex-start;
  gap: 15px;
  margin-top: 20px;
  flex-wrap: wrap;
}

/* Buttons */
.btn-solid, .btn-outline {
  padding: 10px 28px;
  font-weight: 600;
  border-radius: 50px;
  font-family: 'Montserrat', sans-serif;
  transition: 0.3s ease;
  text-decoration: none;
}

.btn-solid {
  background: #168a49;
  color: #fff;
}

.btn-solid:hover {
  background: white;
  color: #168a49;
  transform: translateY(-3px);
  outline: 3px solid #168a49;
}

.btn-outline {
  background-color: var(--secondary);
  border: 2px solid var(--primary);
  color: #fff;
}

.btn-outline:hover {
  background: #fff;
  color: var(--secondary);
  transform: translateY(-3px);
}

    </style>
</head>
<body>
<!-- Always include both -->
<div class="nav-desktop">
  <?php include 'navbar.php'; ?>
</div>

<div class="nav-mobile">
  <?php include 'nav-mobile.php'; ?>
</div>

<style>
/* Hide one based on screen size */
.nav-mobile { display: none; }
@media (max-width: 991px) {
  .nav-desktop { display: none; }
  .nav-mobile { display: block; }
}
</style>

<!-- Hero Section -->
<section class="hero d-flex align-items-center" id="home">
    <div class="hero-overlay"></div>

    <div class="container">
        <div class="row justify-content-start">
            <div class="col-12 col-md-10 col-lg-6">

        <!-- Hero Section -->
        <div class="hero-content text-start px-3 px-md-0">
            <h2 class="fs-1 fs-md-1">Welcome to</h2>
        <h1 class="hero-title">Streamlined Stay Solutions</h1>


        <p class="fs-6 fs-md-5">We provide expert solutions to safeguard what matters most.</p>
        <div class="d-flex justify-content-start gap-2 gap-md-3 hero-btns flex-wrap">
            <a href="#" class="btn btn-solid mb-2 mb-md-0">Learn More</a>
            <a href="#" class="btn btn-outline">Contact Us</a>
        </div>
</div>
<!-- End Hero Section -->


            </div>
        </div>
    </div>
</section>

<section class="image-grid">
  <div class="grid-item" style="background-image: url('images/ir.jpg');">
    <div class="overlay-text">Insurance Relocation</div>
  </div>
  <div class="grid-item" style="background-image: url('images/mr.jpg');">
    <div class="overlay-text">Midterm Rentals</div>
  </div>
  <div class="grid-item" style="background-image: url('images/ch.jpg');">
    <div class="overlay-text">Corporate Housing</div>
  </div>
  <div class="grid-item" style="background-image: url('images/gl.jpg');">
    <div class="overlay-text">Goverment Lodging</div>
  </div>
  <div class="grid-item" style="background-image: url('images/el2.jpg');">
    <div class="overlay-text">Emergency Lodging</div>
  </div>
  <div class="grid-item" style="background-image: url('images/bt.jpg');">
    <div class="overlay-text">Business Travel</div>
  </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const overlays = document.querySelectorAll(".overlay-text");

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          observer.unobserve(entry.target); // run only once
        }
      });
    },
    { threshold: 0.3 } // trigger when 30% visible
  );

  overlays.forEach(overlay => observer.observe(overlay));
});
</script>

<style>
.image-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: repeat(2, 300px); /* 2 rows, each 300px tall */
  gap: 15px;
  padding: 40px;
}

.grid-item {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
}

/* Background image via pseudo-element */
.grid-item::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s ease;
  z-index: 0;
}

/* Allow inline background to pass to ::before */
.grid-item[style]::before {
  background-image: inherit;
}

/* Zoom effect only on picture */
.grid-item:hover::before {
  transform: scale(1.1);
}

/* Animation */
@keyframes fadeUp {
  0% {
    opacity: 0;
    transform: translate(-50%, 30px); /* start lower */
  }
  100% {
    opacity: 1;
    transform: translate(-50%, 0); /* final position */
  }
}

.overlay-text {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  color: var(--light);
  font-size: 1.2rem;
  font-weight: 600;
  text-transform: uppercase;
  background: #716e65;
  padding: 8px 16px;
  border-radius: 100px;

  /* hidden by default */
  opacity: 0;
}

/* Animate only when "show" class is added */
.overlay-text.show {
  animation: fadeUp 0.8s ease forwards;
}

/* Stagger delays */
.grid-item:nth-child(1) .overlay-text.show { animation-delay: 0.2s; }
.grid-item:nth-child(2) .overlay-text.show { animation-delay: 0.4s; }
.grid-item:nth-child(3) .overlay-text.show { animation-delay: 0.6s; }
.grid-item:nth-child(4) .overlay-text.show { animation-delay: 0.8s; }
.grid-item:nth-child(5) .overlay-text.show { animation-delay: 1s; }
.grid-item:nth-child(6) .overlay-text.show { animation-delay: 1.2s; }


/* Responsive (mobile: 1 column) */
@media (max-width: 768px) {
  .image-grid {
    grid-template-columns: 1fr;
    grid-template-rows: auto;
  }
  .grid-item {
    height: 250px;
  }
}

</style>

<section class="offer-section">
  <div class="offer-container">
    
    <!-- Left column -->
    <div class="offer-left">
      <h2 class="offer-title">What Can 3S Offer You</h2>
      <p class="offer-description">
        3S specializes in delivering seamless and fully furnished housing solutions for insurance relocations, 
        travel nurses, corporate personnel, and beyond. Experience comfort and convenience like never before.
      </p>
    </div>

    <!-- Right column -->
    <div class="offer-right">
      <div class="feature">
        <i class="fas fa-calendar-alt"></i>
        <h4>Flexible Cancellation</h4>
        <p>We understand plans change, your apartment rental needs to be the least of your worries!</p>
      </div>
      <div class="feature">
        <i class="fas fa-couch"></i>
        <h4>Fully Furnished Units</h4>
        <p>Relax and unwind in your tastefully decorated unit with all the comforts of home.</p>
      </div>
      <div class="feature">
        <i class="fas fa-star"></i>
        <h4>5 Star Accommodations</h4>
        <p>Between our fully furnished units and variety of on-site amenities you will not want to leave!</p>
      </div>
      <div class="feature">
        <i class="fas fa-key"></i>
        <h4>Contactless Check-In</h4>
        <p>We know travel days are hectic, make your life a little easier with contactless check-in.</p>
      </div>
      <div class="feature">
        <i class="fas fa-wifi"></i>
        <h4>Ultra Fast Wi-Fi</h4>
        <p>Away from the office? No problem! Our ultra fast Wi-Fi will ensure you never miss a thing.</p>
      </div>
      <div class="feature">
        <i class="fas fa-headset"></i>
        <h4>24/7 Customer Service</h4>
        <p>Our friendly staff is here and ready to help with whatever you need day or night!</p>
      </div>
    </div>

  </div>
</section>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>/* Section Layout */
.offer-section {
  padding: 80px 40px;
  background: var(--light); /* subtle background */
  font-family: 'Montserrat', sans-serif;
}

.offer-container {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 50px;
  align-items: start;
  max-width: 1200px;
  margin: 0 auto;
}

/* Left Column */
.offer-left {
  max-width: 500px;
}
.offer-title {
  font-size: 2.8rem;
  font-weight: 700;
  margin-bottom: 20px;
  color: #2b376a;
  line-height: 1.2;
  text-align: right;
}
.offer-description {
  font-size: 1.1rem;
  color: #555;
  line-height: 1.7;
    text-align: right;
}

/* Right Column (features) */
.offer-right {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(3, auto);
  gap: 25px;
}
.feature {
  background: #fff;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-align: left;
}
.feature:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.feature i {
  font-size: 2rem;
  color: #2b376a;
  margin-bottom: 15px;
}
.feature h4 {
    text-transform: uppercase;
  font-size: 1.2rem;
  font-weight: 600;
  color: #2b376a;
  margin-bottom: 10px;
}
.feature p {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.6;
}

/* Responsive */
@media (max-width: 991px) {
  .offer-container {
    grid-template-columns: 1fr;
  }
  .offer-right {
    grid-template-columns: 1fr;
  }
}
</style>

    <!-- Footer -->
    <?php include 'footer.php';?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    


</body>
</html>