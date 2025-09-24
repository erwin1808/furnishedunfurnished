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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

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
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');



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
  min-height: 65vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  color: #fff; /* make text white */
}

/* Overlay */
.hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(53, 60, 100, 0.3); /* semi-transparent black overlay */
  z-index: 1;
}

/* Hero text */
.hero-content {
  position: relative;
  z-index: 2; /* above overlay */
  text-align: center;
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

  transform: translate(-40px, 230px);
}

/* Headings */
.hero-content h1,
.hero-title {
  text-transform: uppercase;
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: -5px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
  white-space: nowrap;  /* keep on one line */
  overflow: visible;    /* allow full text to show */
  text-overflow: clip;  /* remove ellipsis */

}

/* Mobile-first style */
.hero-title {
  font-size: 2.5rem;   /* adjust size for mobile */
  width: 100%;       /* full container width */
}

/* Hero Section Mobile Fixes */
@media (max-width: 768px) {
  .hero-content{
    transform: translate(0, 50px);
    align-items: center;
  }
  .hero-content h1,
  .hero-title {
    white-space: normal; /* Allow text to wrap on mobile */
    text-align: center; /* Center align on mobile */
    line-height: 1.2;
    margin-bottom: 10px;
  }
  
  .hero-title {
    font-size: 2rem !important; /* Slightly larger for better readability */
  }
  
  .hero-content p {
    width: 100% !important; /* Full width on mobile */
    max-width: 100%;
    font-size: 1rem !important;
    text-align: center;
    margin: 0 auto;
    padding: 0 10px;
  }
  
  .hero-content {
    text-align: center !important; /* Center everything on mobile */
    padding: 0 15px;
  }
}

/* Extra small devices */
@media (max-width: 480px) {
  .hero-title {
    font-size: 1.5rem !important;
    line-height: 1.1;
  }
  
  .hero-content p {
    font-size: 0.9rem !important;
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

        <h1 class="hero-title">Insurance Relocation Housing Solutions</h1>


        <p class="fs-5 fs-md-5" style="width: 800px;">Immediate, Comfortable, and Fully Furnished Homes for Displaced Families.</p>
</div>
<!-- End Hero Section -->


            </div>
        </div>
    </div>
</section>

<section class="help-section">
  <div class="container">
    <div class="row title-row">
      <h2>How We Help</h2>
    </div>
    <div class="row description-row">
      <div class="description-box">
        Streamlined Stay Solutions offers premium temporary housing solutions for individuals and families displaced due to natural disasters, fires, floods, and other unforeseen circumstances. We understand the urgency and stress involved, which is why we specialize in providing seamless transitions into fully furnished homes.
      </div>
    </div>
  </div>
</section>

<style>
  .help-section {
    margin-top: -100px;
    height: 500px;
    display: flex;
    flex-direction: column;
    justify-content: center; /* vertically centers the rows */
    padding: 0 40px; /* left and right margin for section content */
    box-sizing: border-box;
  }

  .container {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px; /* spacing between rows */
  }

  .title-row h2 {
    color: #2b376a;
    text-transform: uppercase;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: -5px;
    white-space: nowrap;  /* keep on one line */
    overflow: visible;    /* allow full text to show */
    text-overflow: clip;  /* remove ellipsis */
    transform: translateX(155px);
    text-align: left;
    margin: 0;
    font-size: 42px !important;

  }

  .description-row {
    font-size: 18px;
    display: flex;
    justify-content: center; /* centers the box horizontally */
  }

  .description-box {
    border-radius: 15px;
    border: 1px solid #556799; /* box border */
    padding: 20px;
    max-width: 1000px;
    text-align: left;
    box-sizing: border-box;
    background-color: #f9f9f9; /* optional background for contrast */
  }

  @media (max-width: 768px) {
    .description-box {
      max-width: 100%;
    }
  }
</style>
<section class="why-choose-us">
  <div class="container">
    <h2 class="features-title">Why Choose Us for Insurance <br> Relocation?</h2>
    <div class="features">
      <div class="feature">
        <h3>Fully Furnished Properties</h3>
        <p>All of our homes and apartments come fully equipped with everything your clients need – furniture, kitchenware, linens, and more.</p>
      </div>
      <div class="feature">
        <h3>Fast Placement, Immediate Availability</h3>
        <p>We know time is critical. Our team works swiftly to secure housing within 24 to 48 hours of the request.</p>
      </div>
      <div class="feature">
        <h3>Flexible Lease Terms</h3>
        <p>Whether your clients need a place for a few weeks or several months, we offer flexible terms to meet their situation.</p>
      </div>
      <div class="feature">
        <h3>Prime Locations</h3>
        <p>We provide homes in safe, well-located areas near schools, work, and medical facilities, ensuring minimal disruption to daily life.</p>
      </div>
      <div class="feature">
        <h3>Utilities & Amenities Included</h3>
        <p>All properties include utilities such as Wi-Fi, water, electricity, and gas. Many also offer extra amenities like gyms, pools, and laundry services.</p>
      </div>
    </div>
  </div>
</section>

<style>
  .why-choose-us {
    background-color: #2b376a;
    color: #ffffff;
    padding: 80px 20px;
    position: relative;
    text-align: center;
    border-top-left-radius: 50% 20px;  /* curved top */
    border-top-right-radius: 50% 20px;
  }

  .why-choose-us h2 {
      color: var(--light);
    text-transform: uppercase;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: -5px;
    white-space: nowrap;  /* keep on one line */
    overflow: visible;    /* allow full text to show */
    text-overflow: clip;  /* remove ellipsis */
    text-align: left;
    margin: 0;
    font-size: 42px !important;
    margin-bottom: 50px;
    text-align: center;
   
  }

  .features {
    display: flex;
    flex-direction: column;
    gap: 30px;
    max-width: 900px;
    margin: 0 auto;
    text-align: left;
  }

  .feature h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #ffd700; /* highlight titles */
  }

  .feature p {
    font-size: 16px;
    line-height: 1.6;
    color: #e0e0e0;
  }

  @media (min-width: 768px) {
    .features {
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .feature {
      flex: 1 1 45%; /* two columns on larger screens */
    }
  }
  .submit-request-btn {
  margin-top: 15px;
  padding: 10px 20px;
  background-color: #2b376a;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-request-btn:hover {
  background-color: #556799;
}

</style>

<section class="how-it-works">
  <div class="container">
    <h2>How It Works</h2>
    <p class="subtitle">We Simplify the Relocation Process</p>
    <div class="steps">
   <div class="step">
  <div class="step-number">1</div>
  <div class="step-content">
    <h3>Submit Your Client’s Needs</h3>
    <p>Share your client’s specific requirements, including location, family size, and special needs. We accept requests directly from insurance adjusters, relocation coordinators, or families themselves.</p>
    <button class="submit-request-btn">Submit a Request</button>
  </div>
</div>

      <div class="step">
        <div class="step-number">2</div>
        <div class="step-content">
          <h3>Tailored Property Options</h3>
          <p>We handpick multiple housing options that fit your client’s needs. Each property comes with detailed information and images to ensure they make an informed decision.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">3</div>
        <div class="step-content">
          <h3>Move-In Ready in 24-48 Hours</h3>
          <p>Once the client chooses their property, we handle the rest. Our homes are ready for immediate move-in, ensuring a smooth transition during this difficult time.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">4</div>
        <div class="step-content">
          <h3>24/7 Support Throughout the Stay</h3>
          <p>From check-in to move-out, our dedicated customer service team is available around the clock to assist with any needs or concerns.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
// Intersection Observer for scroll animation
document.addEventListener('DOMContentLoaded', function() {
  const steps = document.querySelectorAll('.how-it-works .step');

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if(entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target); // animate only once
      }
    });
  }, {
    threshold: 0.2 // 20% visible
  });

  steps.forEach(step => observer.observe(step));
});
</script>

<style>
  .how-it-works {
    background-color: #ffffff;
    padding: 80px 20px;
    text-align: center;
  }

  .how-it-works h2 {
 color: #2b376a;
    text-transform: uppercase;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: -5px;
    white-space: nowrap;  /* keep on one line */
    overflow: visible;    /* allow full text to show */
    text-overflow: clip;  /* remove ellipsis */
    text-align: center;
    margin: 0;
    font-size: 42px !important;
  }

  .how-it-works .subtitle {
     font-family: 'Montserrat', sans-serif;
    font-size: 24px;
    margin-bottom: 50px;
    color: #555555;
  }

.steps {
  display: flex;
  flex-direction: column;
  gap: 40px;
  max-width: 900px;
  margin: 0 auto;
  align-items: flex-start; /* align all steps to left */
  text-align: left;        /* ensure content aligns left */
}

.step {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  justify-content: flex-start; /* push content left */
  padding: 20px;
  border-radius: 10px;
  width: 100%;
}
  .step:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    background-color: #f9f9f9;
  }
/* Step animation */
.step {
  opacity: 0;
  transform: translateY(50px);
  transition: all 0.6s ease-out;
}

.step.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Optional: stagger delay for each step */
.step:nth-child(1).visible { transition-delay: 0.1s; }
.step:nth-child(2).visible { transition-delay: 0.3s; }
.step:nth-child(3).visible { transition-delay: 0.5s; }
.step:nth-child(4).visible { transition-delay: 0.7s; }

  .step-number {
    font-size: 36px;
    font-weight: bold;
    color: #2b376a;
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .step-content h3 {
    font-size: 24px;
    margin: 0 0 10px;
    color: #2b376a;
  }

  .step-content p {
    font-size: 16px;
    color: #555555;
    line-height: 1.6;
  }
/* Mobile adjustments */
@media (max-width: 768px) {
  .steps {
    align-items: center; /* optional: center steps on mobile */
    text-align: center;
  }

  .step {
    flex-direction: column;
    gap: 15px;
    padding: 15px;
    width: 100%;
  }

  .step-number {
    margin: 0 auto; /* center number on mobile */
  }
}
  /* Mobile Responsive Fixes */
@media (max-width: 768px) {
  /* Help Section Mobile Fixes */
  .help-section {
    margin-top: -50px;
    height: auto;
    min-height: 500px;
    padding: 0 20px;
  }
  
  .title-row h2 {
    transform: translateX(0);
    white-space: normal;
    font-size: 32px !important;
    text-align: center;
    margin-bottom: 20px !important;
  }
  
  .description-row {
    font-size: 16px;
  }
  
  .description-box {
    padding: 15px;
  }
  
  /* Why Choose Us Mobile Fixes */
  .why-choose-us {
    padding: 60px 15px;
    border-top-left-radius: 50px;
    border-top-right-radius: 50px;
  }
  
  .why-choose-us h2 {
    white-space: normal;
    font-size: 18px !important;
    line-height: 1.5;
    margin-bottom: 30px;
  }
  
  .feature h3 {
    font-size: 20px;
  }
  
  .feature p {
    font-size: 14px;
  }
  
  /* How It Works Mobile Fixes */
  .how-it-works {
      white-space: normal;
    font-size: 18px !important;
    line-height: 1.5;
    margin-bottom: 30px;
  }
  
  .how-it-works h2 {
    font-size: 28px;
    margin-left: -10px;
  }
  
  .how-it-works .subtitle {
    font-size: 18px;
    margin-bottom: 30px;
  }
  
  .step {
    flex-direction: column;
    text-align: center;
    gap: 15px;
    padding: 15px;
  }
  
  .step-number {
    width: 50px;
    height: 50px;
    font-size: 28px;
    margin: 0 auto;
  }
  
  .step-content h3 {
    font-size: 20px;
  }
  
  .step-content p {
    font-size: 14px;
  }
}

/* Additional fixes for very small screens */
@media (max-width: 480px) {
  .help-section {
    margin-top: -30px;
    padding: 0 15px;
  }
  
  .title-row h2 {
    font-size: 26px !important;
  }
  
 
  
  .how-it-works h2 {
    font-size: 24px;
  }
}
</style>

    <!-- Footer -->
    <?php include 'footer.php';?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    


</body>
</html>