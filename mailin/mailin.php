<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mail In | Furnished Unfurnished</title>
  <?php include "../includes/icon.php"; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
  <style>
    /* Base Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #333;
      margin: 0;
      padding: 0;
    }
    
    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
    
    .section-title {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .section-title h2 {
      font-size: 2.2rem;
      color: #0ea5e9;
      margin-bottom: 15px;
    }
    
    .section-title p {
      font-size: 1.1rem;
      color: #666;
    }
    
    /* Mailing Label Section */
    .mailing-label-section {
      max-width: 700px;
      margin: 0 auto;
      background-color: #f4f6f8;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      text-align: center;
    }
    
    .address-box {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 5px;
      margin: 30px 0;
      border-left: 4px solid #0ea5e9;
    }
    
    .address-box p {
      margin: 5px 0;
      font-size: 1.1rem;
    }
    
    .download-button {
      background: #4CAF50;
      color: white;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 4px;
      display: inline-block;
      margin-top: 10px;
      font-weight: bold;
      font-size: 1.1rem;
      transition: all 0.3s ease;
    }
    
    .download-button:hover {
      background: #3e8e41;
      transform: translateY(-2px);
    }
    
    .instructions {
      margin-top: 30px;
      text-align: left;
      padding: 15px;
      background-color: #f0f8ff;
      border-radius: 5px;
    }
    
    .instructions h3 {
      margin-top: 0;
      color: #0ea5e9;
    }
    
    .instructions ol {
      padding-left: 20px;
    }
    
    .cta-box {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 5px;
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<!-- Header -->
<?php include "../includes/header.php" ?>

<!-- Mailing Label Section -->
<section class="contact" id="contact">
  <div class="container">
    <div class="section-title" data-aos="fade-down">
      <h2>General, Non Registered Repair Order</h2>
      <p>Download the mailing label to send in your equipment</p>
    </div>

    <div class="mailing-label-section" data-aos="fade-up">
      <div class="address-box">
        <p><strong>Furnished Unfurnished</strong></p>
        <p>1967 Lockbourne Road Suite B</p>
        <p>Columbus, Ohio 43207</p>
      </div>
      
      <a href="../files/shipping_label.pdf" download class="download-button">
        <i class="fas fa-download"></i> Download Mailing Label
      </a>
      
      <div class="instructions">
        <h3>Shipping Instructions:</h3>
        <ol>
          <li>Download and print the mailing label</li>
          <li>Securely package your dental equipment</li>
          <li>Attach the label to your package</li>
          <li>Ship via your preferred carrier (UPS, FedEx, or USPS)</li>
        </ol>
      </div>
    </div>

    <div class="cta-box" data-aos="fade-up">
      <p>Need immediate assistance or not sure what you need? Email or call us — we're here to help!</p>
      <p><strong>Email:</strong> ohiodentalrepair@gmail.com</p>
      <a href="../services/services.php" class="modern-button" style="display: inline-block; margin-top: 15px;">
        Email Us Now
      </a>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include "../includes/foot.php"; ?>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>
</body>
</html>