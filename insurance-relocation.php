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
    background-color: #faf9f5;
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


<section class="who-we-serve">
  <div class="container">
    <!-- Row 1: Title -->
    <div class="row title-row">
      <h2>Who We Serve</h2>
    </div>

    <!-- Row 2: 3 Columns -->
    <div class="row cards-row">
      <!-- Card 1 -->
      <div class="serve-card">
        <div class="card-image">
          <img src="images/gl.jpg" alt="Insurance Companies & Adjusters">
          <div class="card-overlay">
            <p>We work closely with insurance adjusters and agents to ensure fast, reliable housing placements for policyholders.</p>
          </div>
        </div>
        <h3>Insurance Companies & Adjusters</h3>
      </div>

      <!-- Card 2 -->
      <div class="serve-card">
        <div class="card-image">
          <img src="images/gl.jpg" alt="Families Displaced by Natural Disasters">
          <div class="card-overlay">
            <p>Whether it’s a wildfire, hurricane, or flood, we provide safe, comfortable housing for families needing temporary relocation.</p>
          </div>
        </div>
        <h3>Families Displaced by Natural Disasters</h3>
      </div>

      <!-- Card 3 -->
      <div class="serve-card">
        <div class="card-image">
          <img src="images/gl.jpg" alt="Corporate Insurance Relocation">
          <div class="card-overlay">
            <p>Businesses affected by property damage or disasters can rely on us to house their displaced employees and contractors.</p>
          </div>
        </div>
        <h3>Corporate Insurance Relocation</h3>
      </div>
    </div>
  </div>
</section>

<style>
/* Section Styling */
.who-we-serve {
  display: flex;               /* make it flex */
  flex-direction: column;      /* stack content vertically */
  justify-content: center;     /* vertical center */
  align-items: center;         /* horizontal center */
  padding: 80px 20px;
  background-color: #e6e3d8;
  text-align: center;
  font-family: 'Montserrat', sans-serif;
  min-height: 500px;           /* optional: set section height */
}

.who-we-serve .title-row h2 {
  font-size: 42px;
  color: #2b376a;
  text-transform: uppercase;
  margin-bottom: 60px;
  text-align: center;  /* centers the title */
  width: 100%;         /* ensures centering works */
  transform: translateX(0px);
}



/* Cards Row */
.cards-row {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  justify-content: center;
}

.serve-card {
  position: relative;
  width: 350px;
  cursor: pointer;
  overflow: hidden;
  border-radius: 15px;
  text-align: center;
}

.card-image {
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  width: 100%;
  aspect-ratio: 1/1; /* keep square */
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.5s ease;
}

.card-overlay {
  position: absolute;
  bottom: -100%;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(43, 55, 106, 0.85);
  color: #fff;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  transition: bottom 0.5s ease;
  font-size: 16px;
  line-height: 1.5;
}

.serve-card:hover .card-overlay {
  bottom: 0;
}

.serve-card:hover img {
  transform: scale(1.1);
}

.serve-card h3 {
  margin-top: 15px;
  font-size: 22px;
  color: #2b376a;
}

/* Tablet */
@media (max-width: 992px) {
  .serve-card {
    width: 48%;
  }
}

/* Mobile */
@media (max-width: 576px) {
  .serve-card {
    width: 100%;
  }

  .who-we-serve .title-row h2 {
    font-size: 28px;
    margin-bottom: 40px;
  }

  .card-overlay {
    font-size: 14px;
    padding: 15px;
  }

  .serve-card h3 {
    font-size: 18px;
  }
}
</style><section class="testimonials">
  <div class="container">
    <h2>What Our Clients Say</h2>
    <div class="testimonial-slider">
      <div class="testimonial-track">
        <div class="testimonial active">
          <p>"Streamlined Stay Solutions found us a beautiful home within 24 hours of our house fire. The process was stress-free, and the home was more than we expected."</p>
          <h4>Soraya Anne Price</h4>
          <span>Insurance Claimant</span>
        </div>
        <div class="testimonial">
          <p>"The team helped our family relocate quickly after the flood. Every step was seamless, and we felt taken care of."</p>
          <h4>John Doe</h4>
          <span>Homeowner</span>
        </div>
        <div class="testimonial">
          <p>"Fast, professional, and caring service. Highly recommend Streamlined Stay Solutions for anyone in need."</p>
          <h4>Mary Smith</h4>
          <span>Corporate Client</span>
        </div>
      </div>
      <div class="slider-nav">
        <button class="nav-btn prev-btn" aria-label="Previous testimonial">‹</button>
        <div class="dots">
          <span class="dot active" data-index="0"></span>
          <span class="dot" data-index="1"></span>
          <span class="dot" data-index="2"></span>
        </div>
        <button class="nav-btn next-btn" aria-label="Next testimonial">›</button>
      </div>
    </div>
  </div>
</section>

<style>
.testimonials {
  background-color: #faf9f5;
  padding: 80px 20px;
  text-align: center;
  font-family: 'Montserrat', sans-serif;
  overflow: hidden;
}

.testimonials h2 {
  font-size: 42px;
  color: #2b376a;
  text-transform: uppercase;
  margin-bottom: 60px;
  text-align: center;  /* centers the title */
  width: 100%;         /* ensures centering works */
  transform: translateX(0px);
  font-weight: 700;
}

.testimonial-slider {
  position: relative;
  max-width: 700px;
  margin: 0 auto;
  overflow: hidden;
}

.testimonial-track {
  display: flex;
  transition: transform 0.5s ease-in-out;
  width: 300%; /* 3 testimonials = 300% */
}

.testimonial {
  flex: 0 0 33.333%; /* Each testimonial takes 1/3 of the track */
  padding: 0 20px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 15px;
  opacity: 0.5;
  transform: scale(0.9);
  transition: opacity 0.5s, transform 0.5s;
}

.testimonial.active {
  opacity: 1;
  transform: scale(1);
}

.testimonial p {
  font-size: 18px;
  line-height: 1.6;
  color: #333;
  font-style: italic;
}

.testimonial h4 {
  font-size: 20px;
  color: #2b376a;
  margin: 0;
}

.testimonial span {
  font-size: 16px;
  color: #556799;
}

.slider-nav {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 30px;
  gap: 20px;
}

.nav-btn {
background: transparent;
  color: #2b376a;
  border: none;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s;
}



.dots {
  display: flex;
  gap: 10px;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ccc;
  cursor: pointer;
  transition: background 0.3s;
}

.dot.active {
  background: #2b376a;
}

/* Mobile */
@media (max-width: 768px) {
  .testimonials {
    padding: 60px 15px;
  }
  
  .testimonials h2 {
    font-size: 28px;
  }

  .testimonial {
    padding: 0 10px;
  }

  .testimonial p {
    font-size: 16px;
  }

  .testimonial h4 {
    font-size: 18px;
  }

  .testimonial span {
    font-size: 14px;
  }
  
  .slider-nav {
    gap: 15px;
  }
  
  .nav-btn {
    width: 35px;
    height: 35px;
    font-size: 18px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.testimonial-track');
  const testimonials = document.querySelectorAll('.testimonial');
  const dots = document.querySelectorAll('.dot');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  
  let currentIndex = 0;
  let autoSlideInterval;
  let startX = 0;
  let endX = 0;
  const swipeThreshold = 50; // Minimum swipe distance
  
  // Initialize slider
  function initSlider() {
    updateSlider();
    startAutoSlide();
    
    // Add touch events for swipe
    track.addEventListener('touchstart', handleTouchStart, { passive: true });
    track.addEventListener('touchend', handleTouchEnd, { passive: true });
    
    // Add mouse events for desktop swipe simulation
    track.addEventListener('mousedown', handleMouseDown);
    track.addEventListener('mouseup', handleMouseUp);
  }
  
  // Handle touch start
  function handleTouchStart(e) {
    startX = e.touches[0].clientX;
    clearInterval(autoSlideInterval); // Pause auto-slide during interaction
  }
  
  // Handle touch end
  function handleTouchEnd(e) {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
    startAutoSlide(); // Resume auto-slide
  }
  
  // Handle mouse down (for desktop)
  function handleMouseDown(e) {
    startX = e.clientX;
    clearInterval(autoSlideInterval);
    
    // Prevent text selection during drag
    e.preventDefault();
  }
  
  // Handle mouse up (for desktop)
  function handleMouseUp(e) {
    endX = e.clientX;
    handleSwipe();
    startAutoSlide();
  }
  
  // Process swipe gesture
  function handleSwipe() {
    const diffX = startX - endX;
    
    if (Math.abs(diffX) > swipeThreshold) {
      if (diffX > 0) {
        // Swipe left - next slide
        nextSlide();
      } else {
        // Swipe right - previous slide
        prevSlide();
      }
    }
  }
  
  // Go to next slide
  function nextSlide() {
    currentIndex = (currentIndex + 1) % testimonials.length;
    updateSlider();
  }
  
  // Go to previous slide
  function prevSlide() {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    updateSlider();
  }
  
  // Update slider position and active states
  function updateSlider() {
    // Update track position
    track.style.transform = `translateX(-${currentIndex * (100 / testimonials.length)}%)`;
    
    // Update active testimonial
    testimonials.forEach((testimonial, index) => {
      testimonial.classList.toggle('active', index === currentIndex);
    });
    
    // Update dots
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
  }
  
  // Start auto-slide
  function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
  }
  
  // Event listeners for navigation buttons
  prevBtn.addEventListener('click', prevSlide);
  nextBtn.addEventListener('click', nextSlide);
  
  // Event listeners for dots
  dots.forEach(dot => {
    dot.addEventListener('click', function() {
      currentIndex = parseInt(this.getAttribute('data-index'));
      updateSlider();
      clearInterval(autoSlideInterval);
      startAutoSlide();
    });
  });
  
  // Pause auto-slide on hover
  track.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
  track.addEventListener('mouseleave', startAutoSlide);
  
  // Initialize the slider
  initSlider();
});
</script>
<section class="contact-us">
  <div class="container">
    <h2>Contact Us</h2>
<p class="subtitle">
  Have an urgent request? Our team is ready to assist you 24/7.<br>
  Fill out the form below or call us directly at 
  <a href="tel:+18137336115"><strong>(813) 733 – 6115</strong></a>.
</p>


    <form class="contact-form">
      <div class="form-row">
        <div class="form-group">
          <label for="first-name">First Name *</label>
          <input type="text" id="first-name" name="first-name" placeholder="First">
        </div>
        <div class="form-group">
          <label for="last-name">Last Name *</label>
          <input type="text" id="last-name" name="last-name" placeholder="Last">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" placeholder="Phone">
        </div>
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" placeholder="Message"></textarea>
      </div>

      <button type="submit" class="submit-btn">Submit a Request</button>
    </form>
  </div>
</section>

<style>
.contact-us {
  background-color: #faf9f5;
  padding: 80px 20px;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
}

.contact-us h2 {
  font-size: 42px;
  color: #2b376a;
  text-transform: uppercase;
  margin-bottom: 60px;
  text-align: center;  /* centers the title */
  width: 100%;         /* ensures centering works */
  transform: translateX(0px);
  font-weight: 700;
}

.contact-us .subtitle {
  font-size: 18px;
  color: #556799;
  margin-bottom: 40px;
}

.contact-form {
  max-width: 700px;
  margin: 0 auto;
  text-align: left;
}

.form-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.form-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.form-group label {
  margin-bottom: 5px;
  color: #2b376a;
  font-weight: 500;
}

.form-group input,
.form-group textarea {
  padding: 10px 15px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #2b376a;
  outline: none;
}

.submit-btn {
  background-color: #2b376a;
  color: #fff;
  border: none;
  padding: 15px 30px;
  font-size: 18px;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s;
}

.submit-btn:hover {
  background-color: var(--light);
  color: #2b376a;
  border: 1px solid #2b376a;
}

/* Responsive */
@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
  }
}
</style>
<section class="list-properties">
  <div class="container">
    <h2>List Your Properties with Ease</h2>
    <p class="subtitle">
      Ready to connect with tenants? Simply fill out the form with the details of your available accommodations, 
      and we’ll help match you with the right renters. Whether you’re listing one property or several, we make it 
      simple for you to reach potential tenants quickly and efficiently. Click the button to get started!
    </p>
    <div class="cta-button">
      <a href="#" class="submit-property-btn">Submit a Property</a>
    </div>
  </div>
</section>

<style>
.list-properties {
  background-color: #f5f4f0;
  padding: 80px 20px;
  text-align: center;
  font-family: 'Montserrat', sans-serif;
}

.list-properties h2 {
  font-size: 42px;
  color: #2b376a;
  text-transform: uppercase;
  margin-bottom: 60px;
  text-align: center;  /* centers the title */
  width: 100%;         /* ensures centering works */
  transform: translateX(0px);
  font-weight: 700;
}
.list-properties .subtitle {
  font-size: 20px;
  color: #556799;
  max-width: 700px;
  margin: 0 auto 40px auto;
  line-height: 1.6;
  border: 1px solid #ccc;
  border-radius: 10px;
  padding: 20px; /* adds space inside the box */
  text-align: center; /* centers the text nicely */
}


.cta-button {
  text-align: center;
}

.submit-property-btn {
  display: inline-block;
  background-color: #2b376a;
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  padding: 15px 35px;
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s, transform 0.3s;
}

.submit-property-btn:hover {
  background-color: transparent;
  color: #2b376a;
  border: 1px solid #2b376a;
  transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
  .list-properties h2 {
    font-size: 28px;
  }

  .list-properties .subtitle {
    font-size: 16px;
  }

  .submit-property-btn {
    font-size: 16px;
    padding: 12px 25px;
  }
}
</style>

    <!-- Footer -->
    <?php include 'footer.php';?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    


</body>
</html>