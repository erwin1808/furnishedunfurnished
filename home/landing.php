<?php
include "../includes/db.php";

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home | Ohio Dental Repair</title>
  <?php include "../includes/icon.php"; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
  <style>
    :root {
      --primary: #0066cc;
      --primary-light: #e6f2ff;
      --secondary: #00a0e1;
      --dark: #1a2e4a;
      --light: #f8fafc;
      --gray: #64748b;
      --light-gray: #e2e8f0;
      --white: #ffffff;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
      --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
      --radius-sm: 0.25rem;
      --radius-md: 0.5rem;
      --radius-lg: 1rem;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.7;
    }

    h1, h2, h4 {
      color: var(--dark);
      font-weight: 600;
      line-height: 1.3;
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: all 0.2s ease;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    ul {
      list-style: none;
      padding-left: 0;
    }

    ul li {
      position: relative;
      padding-left: 30px;
      margin-bottom: 12px;
    }

    ul li::before {
      content: "";
      position: absolute;
      left: 0;
      top: 8px;
      width: 16px;
      height: 16px;
      background-color: var(--secondary);
      mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M22 11.08V12a10 10 0 1 1-5.93-9.14'%3E%3C/path%3E%3Cpolyline points='22 4 12 14.01 9 11.01'%3E%3C/polyline%3E%3C/svg%3E");
      mask-repeat: no-repeat;
      mask-position: center;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 2.5rem;
      margin-bottom: 16px;
      position: relative;
      display: inline-block;
    }

    .section-title h2::after {
      content: "";
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      border-radius: 2px;
    }

    .section-title p {
      font-size: 1.1rem;
      color: var(--gray);
      max-width: 700px;
      margin: 0 auto;
    }

    .cta-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: var(--white);
      padding: 14px 32px;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 500;
      text-transform: uppercase;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-md);
      letter-spacing: 0.5px;
      border: none;
      cursor: pointer;
    }

    .cta-button:hover {
      background: linear-gradient(to right, var(--secondary), var(--primary));
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                  url('../images/handpiece-repair-bg.jpeg') center/cover no-repeat;
      color: white;
      text-align: center;
      padding: 120px 20px;
      margin-bottom: 60px;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      font-weight: 700;
      color: var(--white);
    }

    .hero p {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 0 auto 40px;
    }

    /* Features Section */
    .features {
      padding: 100px 0;
      background-color: var(--white);
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .feature-card {
      background: var(--white);
      padding: 40px 30px;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-sm);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-lg);
    }

    .feature-icon {
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 20px;
    }

    /* Testimonials Section */
    .testimonials {
      padding: 100px 0;
      background-color: var(--primary-light);
    }

    .testimonial-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .testimonial-card {
      background: var(--white);
      padding: 40px 30px;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-sm);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-md);
    }

    .testimonial-text {
      font-style: italic;
      margin-bottom: 20px;
      color: var(--gray);
    }

    .testimonial-author {
      font-weight: 600;
      color: var(--primary);
    }

    /* About Section */
    .about {
      padding: 100px 0;
      background-color: var(--white);
      position: relative;
      overflow: hidden;
    }

    .about::before {
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      width: 40%;
      height: 100%;
      background-color: var(--primary-light);
      z-index: 0;
      clip-path: polygon(30% 0, 100% 0, 100% 100%, 0% 100%);
    }

    .about-content {
      position: relative;
      z-index: 1;
    }

    .about-content p {
      margin-bottom: 20px;
      font-size: 1.05rem;
      color: var(--gray);
    }

    .about-content h2 {
      margin: 40px 0 20px;
      font-size: 1.5rem;
      color: var(--primary);
      position: relative;
      padding-bottom: 10px;
    }

    .about-content h2::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      border-radius: 3px;
    }

    .equipment-list {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-bottom: 30px;
    }

    .equipment-column {
      flex: 1;
      min-width: 250px;
      background: var(--light);
      padding: 25px;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-sm);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .equipment-column:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-md);
    }

    .callout {
      border-left: 4px solid var(--secondary);
      background: var(--primary-light);
      padding: 25px;
      font-weight: 500;
      border-radius: var(--radius-sm);
      margin: 30px 0;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .callout i {
      font-size: 1.5rem;
      color: var(--secondary);
    }

    .cta-box {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      padding: 40px;
      border-radius: var(--radius-md);
      text-align: center;
      margin-top: 60px;
      color: var(--white);
      box-shadow: var(--shadow-lg);
    }

    .cta-box p {
      color: var(--white);
      font-size: 1.1rem;
      max-width: 800px;
      margin: 0 auto 25px;
    }

    /* CTA Section */
    .cta-section {
      background-color: var(--dark);
      color: var(--white);
      text-align: center;
      padding: 80px 0;
    }

    .cta-section h2 {
      margin-bottom: 20px;
      font-size: 2.2rem;
    }

    .cta-section h2 span {
      color: #dd7a22;
    }

    .cta-section p {
      max-width: 800px;
      margin: 0 auto 30px;
      color: var(--light-gray);
    }

    /* Footer */
    footer {
      background: var(--dark);
      color: var(--light);
      padding: 70px 0 30px;
      position: relative;
    }

    .footer-content {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
      margin-bottom: 40px;
    }

    .footer-section {
      flex: 1;
      min-width: 250px;
    }

    .footer-section h2 {
      margin-bottom: 25px;
      font-size: 1.3rem;
      color: var(--white);
      position: relative;
      padding-bottom: 10px;
    }

    .footer-section h2::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background: var(--secondary);
    }

    .footer-section p,
    .footer-section a {
      color: var(--light-gray);
      margin-bottom: 12px;
      display: block;
    }

    .footer-section a:hover {
      color: var(--secondary);
      padding-left: 5px;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transition: all 0.3s ease;
    }

    .social-links a:hover {
      background: var(--secondary);
      transform: translateY(-3px);
    }

    .copyright {
      text-align: center;
      padding-top: 30px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 0.9rem;
      color: var(--light-gray);
    }

    @media (max-width: 992px) {
      .about::before {
        width: 60%;
        opacity: 0.5;
      }
    }

    @media (max-width: 768px) {
      .section-title h2 {
        font-size: 2rem;
      }
      
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero p {
        font-size: 1.1rem;
      }
      
      .about {
        padding: 60px 0;
      }
      
      .about::before {
        display: none;
      }
      
      .equipment-list {
        flex-direction: column;
        gap: 20px;
      }
      
      .cta-box {
        padding: 30px 20px;
        margin-top: 40px;
      }
      
      .cta-button {
        padding: 12px 25px;
        font-size: 0.95rem;
      }
    }

    @media (max-width: 576px) {
      .section-title h2 {
        font-size: 1.8rem;
      }
      
      .section-title p {
        font-size: 1rem;
      }
      
      .about-content h2 {
        font-size: 1.3rem;
      }
      
      .hero h1 {
        font-size: 2rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <?php include "../includes/header.php" ?>

  <!-- Hero Section -->
  <section class="hero" data-aos="fade">
    <div class="container">
      <h1>Ohio Dental Repair</h1>
      <p>Professional dental equipment repair and maintenance services to keep your practice running smoothly. Fast, reliable, and affordable solutions for all your dental equipment needs.</p>
      <a href="../services/services.php" class="cta-button">Our Services</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-title" data-aos="fade-down">
        <h2>Why Choose Ohio Dental Repair</h2>
        <p>Providing comprehensive repair services for all dental equipment with unmatched convenience and support</p>
      </div>
      <div class="features-grid">
        <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-icon">
            <i class="fas fa-tools"></i>
          </div>
          <h3>Comprehensive Dental Equipment Repair</h3>
          <p>We service a wide range of dental equipment, including Handpieces, sterilizer,  Scalers, and more.</p>
        </div>
        <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-icon">
            <i class="fas fa-clock"></i>
          </div>
          <h3>Fast & Flexible Repair Requests</h3>
          <p>Manage your repair requests easily online anytime, without disrupting your busy schedule or office routine.</p>
        </div>
        <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <h3>Seamless Communication</h3>
          <p>Use our CONTACT page to describe your equipment needs precisely and get timely feedback from our repair facility.</p>
        </div>
        <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
          <div class="feature-icon">
            <i class="fas fa-certificate"></i>
          </div>
          <h3>Experienced Technicians</h3>
          <p>Our trained technicians are capable of handling repairs for all major dental brands with expertise and care.</p>
        </div>
        <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
          <div class="feature-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Warranty & Peace of Mind</h3>
          <p>All repairs come with warranty protection, ensuring quality service and your confidence.</p>
        </div>
        <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
          <div class="feature-icon">
            <i class="fas fa-clock"></i>
          </div>
          <h3>24/7 Online System</h3>
          <p>Our online repair system is always open, giving you the flexibility to place orders any time and save valuable office time.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <!--section class="testimonials" id="testimonials">
    <div class="container">
      <div class="section-title" data-aos="fade-down">
        <h2>What Our Clients Say</h2>
        <p>Hear from dental professionals who trust us with their equipment</p>
      </div>
      <div class="testimonial-grid">
        <div class="testimonial-card" data-aos="fade-up">
          <p class="testimonial-text">"Ohio Dental Repair saved our practice when our dental chair failed. Their technician arrived quickly and had us back in business the same day!"</p>
          <p class="testimonial-author">- Dr. Maria Santos, DDS</p>
        </div>
        <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
          <p class="testimonial-text">"The quality of their repair work is exceptional. We've used their services for 5 years and never had a callback for the same issue."</p>
          <p class="testimonial-author">- Dr. Robert Lim, Dental Care Center</p>
        </div>
        <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
          <p class="testimonial-text">"Their preventive maintenance program has saved us thousands in emergency repairs. Highly recommended for any dental practice!"</p>
          <p class="testimonial-author">- Dr. Juan Dela Cruz, Smile Clinic</p>
        </div>
      </div>
    </div>
  </section-->

  <!-- About Section -->
  <section class="about" id="about">
    <div class="container">
      <div class="section-title" data-aos="fade-down">
        <h2>About Ohio Dental Repair</h2>
        <p>Expert repair services for the dental industry</p>
      </div>
      <div class="about-content" data-aos="fade-up">
        <p>
          Ohio Dental Repair provides comprehensive repair services for the dental industry, specializing in most dental equipment - not just handpieces. We've revolutionized the repair process by giving our clients complete control through our online system.
        </p>

        <h2>Seamless Repair Management</h2>
        <p>
          Our website allows clients to manage repair requests quickly and efficiently without disrupting their busy schedules. Through our CONTACT page, clients can precisely describe their equipment needs - whether requiring on-site service or shipment to our facility - including their preferred turnaround time.
        </p>

        <h2>Technology-Driven Service</h2>
        <p>By leveraging email and internet technology, we save you valuable office time. Our system enables you to:</p>
        <ul>
          <li>Interact with our repair facility 24/7</li>
          <li>Submit repair orders anytime</li>
          <li>Receive prompt feedback on service requests</li>
          <li>Keep your staff focused on patient care</li>
        </ul>

        <h2>Equipment We Service</h2>
        <p>Our expertise covers a wide range of dental equipment including:</p>
               <div class="equipment-list">
          <div class="equipment-column">
            <ul>
              <li>Most Common Handpieces (High and Slow speed )</li>
              <li>Ultrasonic cleaners</li>
              <li>Sterilizers</li>
              <li>Chair Lighting</li>
            </ul>
          </div>
          <div class="equipment-column">
            <ul>
               <li>Delivery Systems</li>
              <li>Air lines</li>
                <li>Suction  units</li>
              <li>Dental chairs</li>
            </ul>
          </div>
        </div>

        <div class="callout">
          <i class="fas fa-question-circle"></i>
          <div>
            <strong>Not sure if we service your equipment?</strong> Email or call us - we're happy to help!
          </div>
        </div>

        <div class="cta-box" data-aos="zoom-in">
          <p>
Our online system is always available. For questions, simply click on the button below.
          </p>
          <a href="../contact/contact.php" class="cta-button">
            <i class="fas fa-envelope"></i> Contact Us Today
          </a>
        </div>
      </div>
    </div>
  </section>



  <!-- Footer -->
  <?php include "../includes/foot.php"; ?>

  <!-- AOS Animation Script -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      once: true,
      easing: 'ease-out-quad'
    });
  </script>
</body>
</html>