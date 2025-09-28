<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furnished Unfurnished</title>
    
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
                    <h1 class="hero-title">Furnished Unfurnished</h1>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-card">
                            <div class="filter-item">
                                <label>Where are you going?</label>
                                <input type="text" placeholder="Enter a destination" class="filter-input">
                            </div>
                            
                            <div class="filter-item">
                                <label for="move-in-date">Move in date</label>
                                <input type="text" id="move-in-date" class="filter-input datepicker" placeholder="Select move in date">
                            </div>

                            <div class="filter-item" style="position: relative;">
                                <label>Monthly budget</label>
                                <input type="text" id="budget-input" placeholder="Enter budget" class="filter-input">

                                <div class="popup-overlay" id="popup-overlay"></div>

                                <div class="popup-card" id="budget-popup">
                                    <div id="budget-slider"></div>

                                    <div class="price-inputs">
                                        <input type="text" id="budget-min" placeholder="Min. price">
                                        <input type="text" id="budget-max" placeholder="No max price set">
                                    </div>

                                    <div class="buttons">
                                        <button class="cancel" id="cancel-budget">Cancel</button>
                                        <button id="apply-budget">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <button class="filter-btn" style="background-color: #00524e;">
                                Search
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    .hero {
        position: relative;
        background: url('images/27-print-3Q4A6771_11zon.jpg') no-repeat center center/cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(36, 48, 101, 0.30);
        z-index: 1;
    }

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

    .hero-title {
        text-transform: uppercase;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        transform: translate(-250px, 500px);
        width: 1200px;
    }

    .filter-section {
        margin-top: 30px;
        transform: translate(50px, -100px);
    }

    .filter-card {
        background: var(--light);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        width: 1200px;
    }

    .filter-item {
        margin-bottom: 20px;
    }

    .filter-item label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #00524e;
        font-size: 0.9rem;
    }

    .filter-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: #00524e;
        box-shadow: 0 0 0 3px rgba(36, 48, 101, 0.1);
    }

    .filter-btn {
        width: 100%;
        background-color: #00524e;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
    }

    .filter-btn:hover {
        background-color: #1a2450;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(36, 48, 101, 0.3);
    }

    .popup-card {
        display: none;
        position: absolute;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        z-index: 100;
        width: 400px;
        top: 100%;
        left: 0;
        margin-top: 5px;
    }

    .popup-card .price-inputs {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .popup-card .price-inputs input {
        width: 45%;
        padding: 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .popup-card .buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .popup-card button {
        flex: 1;
        margin: 0 5px;
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        background-color: #00524e;
        color: #fff;
        cursor: pointer;
    }

    .popup-card button.cancel {
        background-color: transparent;
        color: #555;
        border: 1px solid #ccc;
    }

    .noUi-target {
        height: 6px;
        border-radius: 3px;
        background: #ddd;
    }

    .noUi-connect {
        background: #00524e;
        height: 6px;
        border-radius: 3px;
    }

    .noUi-handle {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #00524e;
        border: none;
        transform: translateY(-6px);
    }

    .popup-overlay {
        display: none;
        position: fixed;
        top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.3);
        z-index: 50;
    }

    /* Mobile Optimizations */
    @media (max-width: 767px) {
        .hero-title {
            width: 100%;
            transform: none;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
        }
        
        .filter-section {
            transform: none;
            margin-top: 20px;
        }
        
        .filter-card {
            width: 100%;
            padding: 20px;
        }
        
        .filter-input {
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        .popup-card {
            width: 90vw;
            left: 50%;
            transform: translate(-50%, -100%);
        }
        
        .popup-card .price-inputs {
            flex-direction: column;
            gap: 10px;
        }
        
        .popup-card .price-inputs input {
            width: 100%;
        }
        
        .popup-card .buttons {
            flex-direction: column;
            gap: 10px;
        }
        
        .popup-card button {
            margin: 0;
        }
        
        .noUi-handle {
            width: 24px;
            height: 24px;
            transform: translateY(-9px);
        }
    }

    /* Desktop layout preserved */
    @media (min-width: 768px) {
        .hero {
            height: 30vh;
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .filter-card {
     
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }
        
        .filter-item {
            margin-bottom: 0;
        }
        
        .filter-btn {
            margin-top: 0;
            padding: 12px 25px;
        }
    }

    @media (min-width: 1200px) {
        .hero-title {
            font-size: 2.7rem;
        }
    }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Include noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Include noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

<script>
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    const budgetInput = document.getElementById('budget-input');
    const popup = document.getElementById('budget-popup');
    const overlay = document.getElementById('popup-overlay');
    const sliderContainer = document.getElementById('budget-slider');
    const inputMin = document.getElementById('budget-min');
    const inputMax = document.getElementById('budget-max');
    const applyBtn = document.getElementById('apply-budget');
    const cancelBtn = document.getElementById('cancel-budget');

    let sliderInitialized = false;

    budgetInput.addEventListener('click', () => {
        popup.style.display = 'block';
        overlay.style.display = 'block';
        
        if (!sliderInitialized) {
            noUiSlider.create(sliderContainer, {
                start: [0, 10000],
                connect: true,
                range: { 'min': 0, 'max': 10000 },
                step: 100,
                tooltips: [true, true],
                format: {
                    to: (value) => `$${Math.round(value)}`,
                    from: (value) => Number(value.replace('$', ''))
                }
            });

            sliderContainer.noUiSlider.on('update', function(values, handle) {
                inputMin.value = values[0];
                inputMax.value = values[1] === '$10000' ? '' : values[1];
            });

            inputMin.addEventListener('change', () => sliderContainer.noUiSlider.set([inputMin.value, null]));
            inputMax.addEventListener('change', () => sliderContainer.noUiSlider.set([null, inputMax.value || 10000]));

            sliderInitialized = true;
        }
    });

    overlay.addEventListener('click', () => closePopup());
    cancelBtn.addEventListener('click', () => closePopup());

    applyBtn.addEventListener('click', () => {
        budgetInput.value = inputMax.value ? `${inputMin.value} - ${inputMax.value}` : `${inputMin.value} - No max price set`;
        closePopup();
    });

    function closePopup() {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    }
</script>



<!-- How It Works Section -->
<section class="how-it-works py-5 bg-light">
    <div class="container">
        <!-- Section Title -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 text-center">
                <h2 class="display-4 fw-bold" style="color: #00524e;">How It Works</h2>
            
                <p class="lead text-muted max-w-800 mx-auto">
             Furnished Unfinished connects verified property owners and agents with relocation specialists, insurance adjusters, displaced families, corporate vendors, contractors, traveling professionals, and Government agencies who need housing for 30 days or longer. 
</p>
                    <p class="lead text-muted max-w-800 mx-auto">Unlike vacation rental sites, we have no booking fees. Rather, we focus on speed, quality, and rental cost efficiency.

</p>
                    <p class="lead text-muted max-w-800 mx-auto">We offer a secure platform where agents and guests can search, compare, and connect directly with landlords. Once a property is selected, bookings are completed through the preferred channel of both parties. Keeping the connection simple and direct between Guests and the Landlords.

</p>
            </div>
        </div>

      <!-- Process Steps Row -->
<div class="row g-4 justify-content-center text-center">
    <!-- Step 1 -->
    <div class="col-md-4 col-lg-3">
        <div class="position-relative border-box p-3 rounded-4">
            <span class="badge position-absolute top-0 start-0 m-2">List Your Property</span>
            <img src="images/woman-calling-on-smartphone-free-photo.webp" class="step-image mb-3 shadow-sm" alt="Connect">
            <div class="row align-items-center text-start">
                <div class="col-3">
                    <span class="step-number">1</span>
                </div>
                <div class="col-9">
                    <p class="text-muted mb-0">
                        Landlords and managers add their furnished or unfurnished rentals. 
                        Our team verifies each listing to ensure quality and reliability.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="col-md-4 col-lg-3">
        <div class="position-relative border-box p-3 rounded-4">
            <span class="badge position-absolute top-0 start-0 m-2">Search & Match</span>
            <img src="images/S_S-BLOG-9-Reasons-to-Start-Writing4_1f8c3d7b-b3c4-4fc7-82de-acbb1580a043.webp" class="step-image mb-3 shadow-sm" alt="Screen">
            <div class="row align-items-center text-start">
                <div class="col-3">
                    <span class="step-number">2</span>
                </div>
                <div class="col-9">
                    <p class="text-muted mb-0">
                        Traveling professionals, contractors, agents, relocating families, 
                        displaced families, and corporate travelers search by location, dates, 
                        budget, and amenities.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3 -->
    <div class="col-md-4 col-lg-3">
        <div class="position-relative border-box p-3 rounded-4">
            <span class="badge position-absolute top-0 start-0 m-2">Connect Directly</span>
            <img src="images/happy-man-using-laptop-in-a-cafe-AHSF01664.jpg" class="step-image mb-3 shadow-sm" alt="Book">
            <div class="row align-items-center text-start">
                <div class="col-3">
                    <span class="step-number">3</span>
                </div>
                <div class="col-9">
                    <p class="text-muted mb-0">
                        Once a match is found, verified guests contact landlords through the platform. 
                        You close the lease and handle payments off-platform — keeping control of your 
                        property and tenant relationship.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</section>

<style>
/* Border Box */
.border-box {
    border: 1px solid #00524e;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    height: 450px;
    position: relative;
}

/* Step Image */
.step-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 15px;
}

/* Step Number Circle */
.step-number {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: #00524e;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    font-size: 1.2rem;
    font-weight: bold;
}
.badge {
    background-color: #00524e;
    color: white;
    padding: 5px 10px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 50px;
    text-transform: uppercase;
    transform: translate(15px, 15px);
}
</style>


<!-- Landlord CTA Section -->
<section class="landlord-cta">
    <div class="container h-100 d-flex justify-content-center align-items-center" style="max-width: 1200px;">
        <div class="d-flex align-items-center justify-content-center w-100 gap-3 flex-wrap">
            <h3 class=" text-white mb-0">Ready to take the next step as a landlord?</h3>
            <a href="#" class="btn btn-md rounded-pill px-4" style="background-color: #168a49; color: #fff; border: none;">List Your Property</a>
        </div>
    </div>
</section>

<style>
.landlord-cta {
    background-color: #00524e;
    min-height: 75px;
    display: flex;
    align-items: center;
}


.landlord-cta h3 {
    font-size: 1.2rem; /* smaller to fit single row */
    margin-bottom: 0;
}

.landlord-cta .btn:hover {
    background-color: #0f6937;
}
</style>


<!-- Landlord Tools Section -->
<section class="landlord-tools py-5 bg-light">
    <div class="container">

        <!-- Row 1: Title -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h2 class="fw-bold" style="color: #00524e;"> Other services</h2>
            </div>
        </div>

        <!-- Row 2: Description -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <p class="text-muted lead">
                  Explore our suite of landlord tools designed to simplify property management — from tenant screening and rent collection to maintenance tracking and secure document storage.
                </p>
            </div>
        </div>

        <!-- Row 3: 2x2 Grid Cards -->
        <div class="row g-4 justify-content-center mb-4">
            <!-- Card 1 -->
            <div class="col-auto">
                <div class="card fixed-card border-0 shadow-sm rounded-4 d-flex flex-row">
                    <!-- Left: Image -->
                    <div class="card-image">
                        <img src="images/s1.jpeg" alt="Leases & Rental Documents">
                    </div>
                    <!-- Right: Text -->
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="fw-bold mb-2" style="color: #00524e;">Leases & Rental Documents</h5>
                        <p class="text-muted mb-0">
                            Fully customizable leases for dates, rates, deposits, pet policies, house rules, and more. 
                            Access state-specific templates and other legal documents for rental success.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-auto">
                <div class="card fixed-card border-0 shadow-sm rounded-4 d-flex flex-row">
                    <div class="card-image">
                        <img src="images/s2.jpeg" alt="Online Rent Payments">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="fw-bold mb-2" style="color: #00524e;">Online Rent Payments</h5>
                        <p class="text-muted mb-0">
                            Accept rental payments online quickly and securely. Automatic reminders for landlords, 
                            tenants earn credit card points by paying rent online.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-auto">
                <div class="card fixed-card border-0 shadow-sm rounded-4 d-flex flex-row">
                    <div class="card-image">
                        <img src="images/s3.jpeg" alt="Damage Protection">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="fw-bold mb-2" style="color: #00524e;">Damage Protection</h5>
                        <p class="text-muted mb-0">
                            Affordable monthly plans to shield your property from tenant and pet damage. 
                            Peace of mind knowing your property is protected.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-auto">
                <div class="card fixed-card border-0 shadow-sm rounded-4 d-flex flex-row">
                    <div class="card-image">
                        <img src="images/s4.jpeg" alt="Tenant Screening">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="fw-bold mb-2" style="color: #00524e;">Tenant Screening</h5>
                        <p class="text-muted mb-0">
                            Comprehensive credit, criminal, and eviction checks. Make informed decisions when choosing your next tenant.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Button -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-lg rounded-pill px-4">Learn About Our Landlord Tools</a>
            </div>
        </div>

    </div>
</section>

<style>
  @media (max-width: 767px) {
    .fixed-card {
        width: 100% !important;
        flex-direction: column !important;
        height: auto;
    }

    .card-image img {
        width: 100% !important;
        height: 150px !important;
        border-radius: 0.75rem 0.75rem 0 0;
        margin-bottom: 10px;
    }

    .card-body h5 {
        font-size: 1rem;
        text-align: center;
    }

    .card-body p {
        font-size: 0.9rem;
        margin: 0 15px !important; /* override previous left/right margin */
        text-align: center;
        word-wrap: break-word; /* ensure long words don’t overflow */
    }
}

.landlord-tools h2 {
    font-size: 2.5rem;
}

/* Fixed card size */
.fixed-card {
    width: 500px;
    height: 350px;
}

.card-image img {
    width: 200px; /* half of card width */
    height: 100%;
    object-fit: cover;
    border-top-left-radius: 0.75rem;
    border-bottom-left-radius: 0.75rem;
}

.card-body h5 {
    font-size: 1.1rem;
}

.card-body p {
    font-size: 1rem;

    margin-left: 10px;   /* left margin */
    margin-right: 20px;  /* right margin */
}
.btn {
    background-color: #00524e;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
}
.btn:hover {
    background-color: transparent;
    color: #00524e;
    border: 1px solid #00524e;
}

</style>

<section class="text-white py-5 d-flex align-items-center justify-content-center text-center" style="background-color: #00524e; height: 150px;">
  <div class="container">
    <h2 class="display-5 font-weight-bold">No Booking Fees</h2>
    <p class="lead">Save thousands per year vs. booking on short-term rental platforms.</p>
  </div>
</section>
<section> <br> <br></section>
    <!-- Footer -->
    <?php include 'footer.php';?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    


</body>
</html>