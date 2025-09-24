<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Services | Ohio Dental Repair</title>
    <?php include "../includes/icon.php"; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
  
  <style>
    :root {
      --primary: #2a5c8d;
      --secondary: #4a90e2;
      --light: #f8f9fa;
      --dark: #343a40;
      --shadow-light: rgba(0,0,0,0.1);
      --shadow-strong: rgba(0,0,0,0.15);
      --border-radius: 8px;
      --transition-speed: 0.3s;
      --font-heading: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      --font-body: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Reset & base */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      line-height: 1.6;
      color: #333;
      background-color: var(--light);
    }

    a {
      color: var(--secondary);
      text-decoration: none;
      transition: color var(--transition-speed);
    }
    a:hover, a:focus {
      color: var(--primary);
      outline: none;
    }

    /* Container */
    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 20px;
    }
   
    .cta-box {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 5px;
      text-align: center;
      margin-top: 40px;
    }
    
    /* Button Styles */
    .modern-button {
      padding: 14px 40px;
      background: linear-gradient(to right, #0ea5e9, #2563eb);
      border: none;
      border-radius: 30px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .modern-button::after {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.15);
      transition: left 0.3s ease;
    }
    
    .modern-button:hover::after {
      left: 100%;
    }
    
    .modern-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    

    /* Service Section */
    .service-section {
      padding: 70px 0 90px;
      background: white;
      box-shadow: 0 10px 30px var(--shadow-light);
      border-radius: var(--border-radius);
      margin: 0px auto 60px; /* Pull up slightly under header */
      position: relative;
      z-index: 10;
    }

    .service-section h2 {
      color: var(--primary);
      font-weight: 700;
      font-size: 2.25rem;
      margin-bottom: 15px;
      text-align: center;
      letter-spacing: 0.02em;
    }

    .service-section p {
      max-width: 720px;
      margin: 0 auto 40px;
      text-align: center;
      font-size: 1.05rem;
      color: #555;
      line-height: 1.7;
    }

    h2, h4 {
      color: var(--secondary);
      font-weight: 600;
      margin-top: 40px;
      margin-bottom: 10px;
      text-align: center;
    }
    
    .cta-button {
      display: inline-block;
      background-color: #2d9cca;
      color: white;
      padding: 15px 40px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
      transition: all 0.3s;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .cta-button:hover {
      background-color: #247ba0;
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    /* CTA Section */
.cta-section {
  padding: 80px 0;
  background-image: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)),
                    url('../images/handpiece-repair-bg.jpeg');
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  color: white;
  text-align: center;
}
    
    .cta-section h2 {
      font-size: 2.2rem;
      margin-bottom: 20px;
    }
    
    .cta-section p {
      max-width: 700px;
      margin: 0 auto 30px;
      font-size: 1.1rem;
    }
    
    /* Pricing table */
    .pricing-table {
      width: 100%;
      max-width: 900px;
      margin: 20px auto 0;
      border-collapse: separate;
      border-spacing: 0 10px;
      box-shadow: 0 4px 20px var(--shadow-strong);
      border-radius: var(--border-radius);
      overflow: hidden;
    }

    .pricing-table thead tr {
      background-color: var(--primary);
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .pricing-table th, .pricing-table td {
      padding: 18px 20px;
      text-align: left;
      font-weight: 500;
    }

    .pricing-table tbody tr {
      background-color: #fafafa;
      border-radius: var(--border-radius);
      box-shadow: 0 2px 8px var(--shadow-light);
      transition: background-color var(--transition-speed);
      cursor: default;
    }
    .pricing-table tbody tr:hover {
      background-color: #f0f8ff;
      box-shadow: 0 4px 14px rgba(42,92,141,0.3);
    }

    .pricing-table tbody tr td {
      border: none;
      border-radius: var(--border-radius);
    }

    /* Tabs */
    .service-tabs {
      display: flex;
      justify-content: center;
      margin-bottom: 40px;
      box-shadow: 0 3px 10px var(--shadow-light);
      background-color: #f7f9fc;
      border-radius: var(--border-radius);
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      overflow: hidden;
    }

    .service-tab {
      flex: 1;
      padding: 14px 24px;
      background-color: transparent;
      border: none;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--primary);
      cursor: pointer;
      transition: background-color var(--transition-speed), color var(--transition-speed);
      user-select: none;
    }

    .service-tab:hover {
      background-color: var(--secondary);
      color: white;
    }

    .service-tab.active {
      background-color: var(--primary);
      color: white;
      box-shadow: inset 0 -4px 8px var(--secondary);
      cursor: default;
    }

    /* Tab content */
    .tab-content {
      display: none;
      animation: fadeIn 0.6s ease forwards;
    }
    .tab-content.active {
      display: block;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    /* Responsive */
    @media (max-width: 900px) {
      .pricing-table {
        max-width: 100%;
        font-size: 0.9rem;
      }

      .service-header h1 {
        font-size: 2.2rem;
      }

      .service-tabs {
        max-width: 100%;
      }
    }

    @media (max-width: 600px) {
      .service-tab {
        font-size: 1rem;
        padding: 12px 16px;
      }

      .service-section p {
        font-size: 1rem;
        padding: 0 10px;
      }

      .pricing-table th, .pricing-table td {
        padding: 12px 10px;
      }
    }

    /* Footer styles moved here to ensure they take precedence */
    footer {
      background-color: #1a252f;
      color: white;
      padding: 50px 0 20px;
    }

    .footer-content {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .footer-section {
      flex: 1;
      min-width: 250px;
      margin-bottom: 20px;
      padding: 0 15px;
    }

    .footer-section h2 {
      margin-bottom: 20px;
      font-size: 1.2rem;
      color: #e67e22;
    }

    .footer-section p, .footer-section a {
      color: #ecf0f1;
      margin-bottom: 10px;
      display: block;
      text-decoration: none;
    }

    .footer-section a:hover {
      color: #e67e22;
    }

    .social-links a {
      display: inline-block;
      margin-right: 15px;
      color: #ecf0f1;
      font-size: 1.2rem;
      transition: color 0.3s;
    }

    .social-links a:hover {
      color: #e67e22;
    }

    .copyright {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid #34495e;
      font-size: 0.9rem;
      color: #bdc3c7;
    }

    #map {
      height: 250px;
      width: 40%;
    }

    @media (max-width: 768px) {
      #map {
        height: 150px;
        width: 100%;
      }
    }

    .leaflet-control-attribution {
      font-size: 11px;
    }
  </style>
</head>
<body>

<!-- Header -->
<?php include "../includes/header.php" ?>

<!-- Service Tabs -->
<section class="service-section" data-aos="fade-up" data-aos-delay="200">
  <div class="container">
    <div class="service-tabs" role="tablist" aria-label="Service Categories">
      <button role="tab" aria-selected="true" aria-controls="high-speed" id="tab-high-speed" class="service-tab active" onclick="openTab(event, 'high-speed')">High-Speed Repair</button>
      <button role="tab" aria-selected="false" aria-controls="low-speed" id="tab-low-speed" class="service-tab" onclick="openTab(event, 'low-speed')">Low-Speed Repair</button>
    </div>
    
<div id="high-speed" role="tabpanel" aria-labelledby="tab-high-speed" class="tab-content active">
  <h2>High-Speed Dental Handpiece Repair</h2>
  <p>At Ohio Dental Repair, we understand the critical role high-speed dental handpieces play in the efficiency and success of your dental practice. These precision instruments are essential for various dental procedures, from routine cleaning to complex restorative work. However, like any mechanical device, they require regular maintenance and occasional repair to ensure optimal performance.</p>
  
  <h2>Pricing</h2>
  <h4>High-Speed Turbine Overhaul / New Turbine Pricing</h4>
  
  <table class="pricing-table" summary="Pricing details for high-speed dental handpiece repair services">
    <thead>
      <tr>
        <th>Service/Make/Manufacturer</th>
        <th>Overhaul ($)</th>
        <th>New Turbine ($)</th>
        <th>Warranty</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Connect to your DB - adjust connection parameters
    include "../includes/db.php";
      if ($mysqli->connect_errno) {
          echo "<tr><td colspan='4'>Failed to connect to database: " . $mysqli->connect_error . "</td></tr>";
      } else {
          // Prepare statement to prevent SQL injection
          $stmt = $mysqli->prepare("SELECT service_make_manufacturer, basic_service, complete_overhaul, warranty FROM service WHERE type = ? AND is_deleted = 0");
          $type = "Highspeed Handpiece";
          $stmt->bind_param("s", $type);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['service_make_manufacturer']) . "</td>";
                  echo "<td>$" . number_format($row['basic_service'], 2) . "</td>";
                  echo "<td>$" . number_format($row['complete_overhaul'], 2) . "</td>";
                  echo "<td>" . htmlspecialchars($row['warranty']) . "</td>";
                  echo "</tr>";
              }
          } else {
            echo "<tr><td colspan='4' style='text-align:center;'>No pricing information available.</td></tr>";

          }

          $stmt->close();
          $mysqli->close();
      }
      ?>
    </tbody>
  </table>
</div>

    
    <!-- Low-Speed Tab Content -->
    <div id="low-speed" role="tabpanel" aria-labelledby="tab-low-speed" class="tab-content">
      <h2>Low-Speed Dental Handpiece Repair</h2>
      <p>Our low-speed handpiece repair services ensure your equipment runs smoothly for procedures requiring precision and control. We service all major brands and models, providing comprehensive maintenance and repair to extend the life of your instruments.</p>
      
      <h2>Pricing</h2>
      <h4>Low-Speed Motor Repair Pricing</h4>
      
      <table class="pricing-table" summary="Pricing details for low-speed dental handpiece repair services">
       <thead>
      <tr>
        <th>Service/Make/Manufacturer</th>
        <th>Overhaul ($)</th>
        <th>New Turbine ($)</th>
        <th>Warranty</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Connect to your DB - adjust connection parameters
    include "../includes/db.php";
      if ($mysqli->connect_errno) {
          echo "<tr><td colspan='4'>Failed to connect to database: " . $mysqli->connect_error . "</td></tr>";
      } else {
          // Prepare statement to prevent SQL injection
          $stmt = $mysqli->prepare("SELECT service_make_manufacturer, basic_service, complete_overhaul, warranty FROM service WHERE type = ? AND is_deleted = 0");
          $type = "Lowspeed Handpiece";
          $stmt->bind_param("s", $type);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['service_make_manufacturer']) . "</td>";
                  echo "<td>$" . number_format($row['basic_service'], 2) . "</td>";
                  echo "<td>$" . number_format($row['complete_overhaul'], 2) . "</td>";
                  echo "<td>" . htmlspecialchars($row['warranty']) . "</td>";
                  echo "</tr>";
              }
          } else {
           echo "<tr><td colspan='4' style='text-align:center;'>No pricing information available.</td></tr>";

          }

          $stmt->close();
          $mysqli->close();
      }
      ?>
    </tbody>
      </table>
    </div>

  </div>
      <div class="cta-box" data-aos="fade-up">
      <p>Need immediate assistance or not sure what you need? Email or call us — we're here to help!</p>
      <!--p><strong>Email:</strong> ohiodentalrepair@gmail.com</p-->
      <!--p><strong>Phone:</strong> (614) 306-9986</p-->
      <a href="https://mail.google.com/mail/?view=cm&fs=1&to=ohiodentalrepair@gmail.com" target="_blank" class="modern-button" style="display: inline-block; margin-top: 15px;">
        Email Us Now
      </a>
    </div>
</section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Need On-Site Dental Equipment Repair?</h2>
            <p>Don't let equipment failures disrupt your practice. Contact us today for fast, reliable dental equipment repair services.</p>
            <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ensure session is started
}


$onsite_link = isset($_SESSION['userid']) ? '../onsite/onsite_user.php' : '../onsite/onsite.php';
?>

<a href="<?php echo $onsite_link; ?>" class="cta-button">Schedule Service</a>

        </div>
    </section>
<!-- Footer -->
<?php include "../includes/foot.php"; ?>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init({ duration: 900, once: true });

  function openTab(event, tabName) {
    // Hide all tab contents
    var tabContents = document.getElementsByClassName("tab-content");
    for (var i = 0; i < tabContents.length; i++) {
      tabContents[i].classList.remove("active");
    }

    // Remove active class from all tabs & update aria-selected
    var tabs = document.getElementsByClassName("service-tab");
    for (var i = 0; i < tabs.length; i++) {
      tabs[i].classList.remove("active");
      tabs[i].setAttribute("aria-selected", "false");
    }

    // Show the selected tab content and mark tab as active
    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
    event.currentTarget.setAttribute("aria-selected", "true");
  }
</script>

</body>
</html>