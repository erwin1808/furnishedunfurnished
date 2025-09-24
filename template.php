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
            --light: #ffffff;    /* Off-white */
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
    min-height: 100vh;       /* use only min-height */
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
     transform: translateX(-50px) !important;
    position: relative;
    z-index: 2;
    max-width: 700px;
    padding: 20px;
    text-align: center;
    color: var(--light);
    animation: fadeInUp 1s ease forwards;
    left: 0%;

    /* Center content vertically */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Headings */
.hero-content h1 {
    width: 700px;
    text-transform: uppercase;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    margin-top: -20px;
}
.hero-title {
  
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  line-height: 1.2;
  text-transform: uppercase;
  margin-top: 0;
  margin-bottom: 15px;
  font-size: 3rem !important;  /* default (mobile) */
  width: 700px !important;
  white-space: nowrap; /* forces single line */
}


@media (min-width: 768px) {
  .hero-title {
    font-size: 3rem; /* tablet and up */
  }
}

@media (min-width: 1200px) {
  .hero-title {
    font-size: 3.5rem; /* large desktops */
  }
}

.hero-content h2 {
 
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}
/* Paragraphs */
.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 25px;
    font-weight: 500;
    text-shadow: 0 2px 6px rgba(0,0,0,0.4);
}

/* Buttons wrapper */
.hero-btns {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px; /* reduced margin from 100px to 20px */
    flex-wrap: wrap;
}

/* Buttons */
.btn-solid, .btn-outline {
    padding: 12px 35px;
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


/* Responsive */
@media (max-width: 992px) {
    .hero-content h1 {
        font-size: 2.2rem;
    }
    .hero-content p {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .hero-content {
        padding: 0 20px;
    }
    .btn-solid, .btn-outline {
        padding: 10px 25px;
        font-size: 0.9rem;
    }
}
        
        /* About Section */
        .about {
            position: relative;
            overflow: hidden;
            padding: 80px 20px;
        }
        
        .about-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 50px;
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-img {
            position: relative;
            flex: 1;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            background: #fff;
        }
        .custom-icon {
    background-color: #1b75bb;
    color: #fff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem; /* adjust icon size */
}

        .about-img img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .about-img .overlay-name {
            position: absolute;
            bottom: -60px;
            left: 0;
            width: 100%;
            padding: 16px 20px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
            font-family: 'Commuters Sans', sans-serif;
            opacity: 0;
            transition: all 0.4s ease;
        }
        
        .about-img:hover .overlay-name {
            bottom: 0;
            opacity: 1;
        }
        
        .about-text {
            flex: 1.2;
            min-width: 300px;
        }
        
        .about-text h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #0B47A8;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }
        
        .about-text h2::after {
            content: "";
            width: 60px;
            height: 4px;
            background: #0B47A8;
            display: block;
            margin-top: 10px;
            border-radius: 2px;
        }
        
        .about-text p {
            font-size: 1rem;
            margin: 18px 0;
            color: #333;
            line-height: 1.8;
            font-family: 'Montserrat', sans-serif;
            text-align: justify;
        }
        
        .about-btn-container {
            margin-top: 25px;
        }
        
        .call-btn {
            font-family: 'Montserrat', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: #1ca659;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(11, 71, 168, 0.25);
        }
        
        .call-btn:hover {
            background: #017e39ff;
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 22px rgba(11, 71, 168, 0.35);
        }
        
        .about-features {
            margin-top: 30px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .feature-text h4 {
            color: #111;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .feature-text p {
            font-size: 0.95rem;
            color: #555;
        }
        
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        
        .scroll-animate.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .from-left {
            transform: translateX(-50px);
        }
        
        .from-right {
            transform: translateX(50px);
        }
        
        .delay-1 { transition-delay: 0.3s; }
        .delay-2 { transition-delay: 0.6s; }
        .delay-3 { transition-delay: 0.9s; }
        
        /* Services Section */
        .services {
            background: var(--light);
            position: relative;
        }
        
        .service-card {
            background: var(--darker);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--accent);
            height: 420px;
        }
        
        .service-img {
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        
        .service-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .service-card:hover .service-img img {
            transform: scale(1.1);
        }
        
        .service-title-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: rgba(15, 23, 42, 0.7);
            z-index: 2;
            transition: opacity 0.3s ease;
        }
        
        .service-title-container h3 {
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            font-size: 1.5rem;
            margin: 0;
            color: var(--light);
        }
        
        .service-card:hover .service-title-container {
            opacity: 0;
            pointer-events: none;
        }
        
        .service-content {
            padding: 25px;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        
        .service-card:hover .service-content {
            opacity: 1;
        }
        
        .service-content p {
            font-family: 'Montserrat', sans-serif;
            color: var(--light-gray);
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .service-content h3 {
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            color: var(--light-gray);
        }
        
        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .service-price {
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--light);
            font-size: 1.1rem;
        }
        
        .service-link {
            color: var(--success);
            font-weight: 600;
            display: flex;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
        }
        
        .service-link i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }
        
        .service-link:hover i {
            transform: translateX(3px);
        }
        
        /* Clients Section */
        .clients {
            background: var(--light);
            padding: 60px 0;
        }
        
        .client-logo {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            max-width: 160px;
            max-height: 80px;
            object-fit: contain;
            filter: grayscale(100%) brightness(0.8);
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .client-logo.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .client-logo:hover {
            filter: grayscale(0) brightness(1);
            opacity: 1;
            transform: scale(1.05);
        }
        
        .view-all-partners-container {
            margin-top: 40px;
            text-align: center;
        }
        
        .view-all-partners-btn {
            background-color: #1ca659;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            height: 50px;
        }
        
        .view-all-partners-btn:hover {
            background-color: #017e39ff;
        }
        
        /* Coverage Map Section */
        .coverage {
            background: #e2e2e2;
            position: relative;
        }
        
        .singapore-map {
            position: relative;
            margin: 40px auto;
            height: 700px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .coverage-stats {
            background: linear-gradient(80deg, var(--dark), var(--light-gray));
            color: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 1200px;
            margin: 40px auto 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: center;
        }
        
        .stat-item {
            padding: 15px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--accent);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .stat-number.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px var(--darker);
        }
        
        /* Contact Section */
        .contact {
            background: #e0e6ec;
            position: relative;
            overflow: hidden;
        }
        
        .contact h2 {
            color: #0B47A8;
        }
        
        .contact:before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: var(--accent);
            opacity: 0.1;
            border-radius: 50%;
            z-index: 0;
        }
        
        .contact-info {
            background-color: #e0e6ec;
            padding: 40px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-family: 'Montserrat', sans-serif;
        }
        
        .contact-info h3 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: #0B47A8;
            font-family: 'Montserrat', sans-serif;
        }
        
        .contact-info p {
            margin-bottom: 25px;
            color: #333;
            line-height: 1.7;
            font-family: 'Montserrat', sans-serif;
        }
        
        .contact-info ul li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }
        
        .contact-info ul li i {
            margin-right: 15px;
            color: #333;
            font-size: 1.2rem;
            margin-top: 3px;
        }
        
        .contact-form {
            background-color: #e0e6ec;
            padding: 40px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--light);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--light);
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(243, 243, 243, 0.2);
        }
        
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        
        /* Flip Card Section */
        .flip-card-section {
            background-color: #fff;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 50px 5%;
            font-family: 'Montserrat', sans-serif;

        }
        
        .flip-header {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .flip-title {
            word-spacing: 2px;
            font-size: 2.5rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            color: #0B47A8;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            font-family: 'Commuters Sans', sans-serif;
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .flip-subtitle {
            font-size: 1.1rem;
            color: #555;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease 0.3s, transform 0.8s ease 0.3s;
        }
        
        .flip-title.show,
        .flip-subtitle.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .flip-card {
            background: transparent;
            width: 320px;
            height: 300px;
            perspective: 1000px;
        }
        
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .flip-card-front h3 {
            background: #0B47A8;
            color: white;
            font-size: 1.2rem;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
        }
        
        .flip-card-back p {
            font-family: 'Montserrat', sans-serif;
        }
        
        .flip-card-front {
            color: white;
            font-size: 1.2rem;
            text-transform: uppercase;
            border-radius: 10px;
        }
        
        .flip-card:nth-of-type(1) .flip-card-front {
            background: #edf5faff;
            color: #0B47A8;
        }
        
        .flip-card:nth-of-type(2) .flip-card-front {
            background: #edf5faff;
            color: #0B47A8;
        }
        
        .flip-card:nth-of-type(3) .flip-card-front {
            background: #0B47A8;
            color: white;
        }
        
        .flip-card-front h3 {
            padding: 10px;
            width: 100%;
            margin: 0;
        }
        
        .flip-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 15px;
        }
        
        .flip-card-back {
            background: white;
            color: #333;
            transform: rotateY(180deg);
            border: 1px solid #ddd;
        }
        
        .flip-card-back p {
            margin-bottom: 15px;
        }
        
        .flip-card-back a {
            color: #0B47A8;
            text-decoration: none;
            font-weight: bold;
        }
        
        .flip-card-back a:hover {
            text-decoration: underline;
        }
        
        /* FAQ Section */
        .faq-section {
            padding: 40px 300px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .faq-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .faq-header h2 {
            font-size: 2.6rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            color: #0B47A8;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            font-family: 'Commuters Sans', sans-serif;
        }
        
        .faq-body {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .faq-column {
            flex: 1;
            min-width: 300px;
        }
        
        .faq-item {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: background 0.3s ease;
        }
        
        .faq-item:hover {
            background: #f1f1f1;
        }
        
        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1.1rem;
            margin-left: 20px;
        }
        
        .toggle-icon {
            font-size: 1.2rem;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.3s ease;
            padding-right: 5px;
            margin-top: 0;
            font-weight: 400;
            line-height: 1.5;
            text-align: justify;
            padding-right: 70px;
            margin-left: 20px;
        }
        
        .faq-item.active .faq-answer {
            max-height: 200px;
            opacity: 1;
            margin-top: 8px;
        }
        
        .faq-item.active .toggle-icon {
            color: #555;
        }
   
      

    </style>
</head>
<body>
    <!-- Top Contact Info -->
    <div class="top-contact" style="display: none;">
        <div class="contact-container">
            <div class="contact-left">
                <a href="https://mail.google.com/mail/?view=cm&to=info@Streamlined Stays GroupSG.com.sg" target="_blank" style="text-decoration: none; color: inherit;">
                    <i class="fas fa-envelope"></i> info@Streamlined Stays GroupSG.com.sg
                </a>
                <a href="https://wa.me/6568888888" target="_blank" style="margin-left: 15px; text-decoration: none; color: inherit;">
                    <i class="fas fa-phone-alt"></i> +65 6888 8888
                </a>
            </div>
        </div>
    </div>
<?php include 'navbar.php'; ?>
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


<!--section class="flip-card-section py-15" style="position: relative; z-index: 9999; transform: translateY(-130px); background-color: transparent; margin-bottom: -150px;">
    <div class="container">
        <div class="row justify-content-center g-4">

      
            <div class="col-12 col-sm-6 col-md-4">
                <div class="flip-card mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                            <img src="assets/images/protect.png" alt="Home Insurance" class="flip-icon">
                            <h3 style="background: #1b76bbcc;">Home Insurance</h3>
                        </div>
                        <div class="flip-card-back d-flex flex-column justify-content-center align-items-center text-center">
                            <p>Homeownership is a dream for many people and is a huge achievement. A home is the biggest investment...</p>
                            <a href="#">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>

      
            <div class="col-12 col-sm-6 col-md-4">
                <div class="flip-card mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                            <img src="assets/images/car-insurance-icon.png" alt="Auto Insurance" class="flip-icon">
                            <h3>Auto Insurance</h3>
                        </div>
                        <div class="flip-card-back d-flex flex-column justify-content-center align-items-center text-center">
                            <p>Stay protected on the road with coverage that meets North Carolina's legal requirements and fits your budget.</p>
                            <a href="#">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="col-12 col-sm-6 col-md-4">
                <div class="flip-card mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                            <img src="assets/images/medical-insurance.png" alt="Life Insurance" class="flip-icon">
                            <h3>Life Insurance</h3>
                        </div>
                        <div class="flip-card-back d-flex flex-column justify-content-center align-items-center text-center">
                            <p>Provide financial security for your loved ones and peace of mind for the future.</p>
                            <a href="#">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section-->


<!-- About Section -->
<section class="about section-padding py-5" id="about" 
         style="border-top-left-radius: 50px; border-top-right-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 50px;">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Image Column -->
            <div class="col-lg-5 scroll-animate from-left delay-1">
                <div class="position-relative">
                    <img src="assets/images/pawa.jpg" alt="About Our Company" class="img-fluid rounded-4 shadow-lg">
                    <span class="overlay-name position-absolute top-100 start-50 translate-middle-x bg-dark text-white px-3 py-2 rounded-2 opacity-0 transition-1">
                        Roman Pawa
                    </span>
                </div>
            </div>

            <!-- Text Column -->
            <div class="col-lg-7 scroll-animate from-right delay-1">
                <h2 class="mb-4 fw-bold display-6" style="color: #1b75bb;">About Streamlined Stays Group</h2>
                <p class="mb-3 text-muted">
                  At Streamlined Stays Group, we strive to thoroughly understand the unique needs of our clients, both in the short and long term, ensuring they can make informed decisions when choosing the right insurance coverage. We appreciate that securing insurance for your loved ones and business is a vital, emotional decision, and we are here to provide customized solutions that meet your specific requirements at a competitive rate.
                </p>
                <p class="mb-3 text-muted">
                  With our years of experience and commitment to exceptional customer service, we are dedicated to offering helpful information, personalized support, and ongoing guidance. Our success is built on cultivating relationships, focusing on client satisfaction, and growing through referrals.
                </p>
                <p class="mb-4 text-muted">
We are proud to be your trusted partner in protecting what matters most—both now and for generations to come.
                </p>

                <!-- Call-to-action Button -->
                <div class="mb-5">
                    <a href="tel:+1XXXXXXXXXX" 
                       class="btn btn-danger btn-lg d-inline-flex align-items-center gap-2 shadow-sm" 
                       style="border-radius: 25px;">
                        <i class="fa-solid fa-phone"></i> Call Us Now
                    </a>
                </div>

 <!-- Feature Highlights 
<div class="row g-3">
    <div class="col-md-6">
        <div class="d-flex align-items-start gap-3">
            <div>
                <h5 class="fw-bold mb-1">Trusted Protection</h5>
                <p class="mb-0 text-muted small">Secure solutions designed for your peace of mind.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex align-items-start gap-3">
            <div>
                <h5 class="fw-bold mb-1">Expert Support</h5>
                <p class="mb-0 text-muted small">Personalized guidance for every insurance need.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex align-items-start gap-3">
            <div>
                <h5 class="fw-bold mb-1">Competitive Rates</h5>
                <p class="mb-0 text-muted small">Affordable insurance solutions without compromising quality.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex align-items-start gap-3">
            <div>
                <h5 class="fw-bold mb-1" style="font-family: 'Commuter Sans', sans-serif;">Custom Coverage</h5>
                <p class="mb-0 text-muted small">Tailored insurance plans to suit your unique needs.</p>
            </div>
        </div>
    </div>
</div>
-->

            </div> <!-- End Text Column -->

        </div>
    </div>
</section>



<script>
// Intersection Observer for scroll animation
const scrollElements = document.querySelectorAll('.scroll-animate');

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if(entry.isIntersecting) {
            entry.target.classList.add('show');
            observer.unobserve(entry.target); // optional: stop observing once shown
        }
    });
}, { threshold: 0.2 }); // trigger when 20% of the element is visible

scrollElements.forEach(el => observer.observe(el));
</script>


<!-- Clients Section -->
<section class="clients section-padding py-5" id="clients" style="background: #e6e3d8; border-top-left-radius: 25px; border-top-right-radius: 25px; border-bottom-left-radius: 100px; border-bottom-right-radius: 100px;">
    <div class="container text-center">
        <h2 class="section-title mb-3">Our Partners</h2>
        <p class="section-subtitle mb-5">
            We contract with the best in the market to give you optimal choices. <br> 
            Maximize your savings and your coverage at the same time!
        </p>

        <!-- Carousel wrapper -->
        <div id="clientsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- First slide -->
                <div class="carousel-item active">
                    <div class="row justify-content-center align-items-center g-4">
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/AIG_InsureNOW (1).png" class="img-fluid" alt="AIG">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/JH_InsureNOW.png" class="img-fluid" alt="JH">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/LG-logo (1).png" class="img-fluid" alt="LG">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoAssurity.png" class="img-fluid" alt="Assurity">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoLCN.png" class="img-fluid" alt="LCN">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoMassMutual.png" class="img-fluid" alt="MassMutual">
                        </div>
                    </div>
                </div>

                <!-- Second slide -->
                <div class="carousel-item">
                    <div class="row justify-content-center align-items-center g-4">
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoNationwide.png" class="img-fluid" alt="Nationwide">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoNorthAmerican.png" class="img-fluid" alt="North American">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoPRU.png" class="img-fluid" alt="PRU">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoSecurian.png" class="img-fluid" alt="Securian">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoSymetra.png" class="img-fluid" alt="Symetra">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/logoTransAmerica.png" class="img-fluid" alt="TransAmerica">
                        </div>
                    </div>
                </div>

                <!-- Third slide -->
                <div class="carousel-item">
                    <div class="row justify-content-center align-items-center g-4">
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/mutual-of-omaha-logo.png" class="img-fluid" alt="Mutual of Omaha">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/PacLife-logo.jpg" class="img-fluid" alt="PacLife">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/Principal_InsureNOW1.png" class="img-fluid" alt="Principal">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/Protective_CTC.png" class="img-fluid" alt="Protective">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <img src="assets/logos/SBLI-Logo_294U_sm1907.png" class="img-fluid" alt="SBLI">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#clientsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#clientsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- View All Partners Button -->
        <div class="mt-4">
            <a href="all-partners.html" class="btn btn-lg" style=" background-color: #39afda; color: #ffff; border-radius: 25px;">View All Partners</a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: #1b75bb; font-size: 2.5rem;">Why Choose Streamlined Stays</h2>
            <p class="text-muted fs-6">
                Like any combo, your home and auto insurance policies belong together.<br>
                Whatever your insurance coverage needs are, we're here to help life go right.  
                <span style="color:#1b75bb; cursor:pointer; text-decoration:none; font-weight:500;" 
                    onmouseover="this.style.textDecoration='underline'" 
                    onmouseout="this.style.textDecoration='none'">Get a quote</span> 
                or 
                <span style="color:#1b75bb; cursor:pointer; text-decoration:none; font-weight:500;" 
                    onmouseover="this.style.textDecoration='underline'" 
                    onmouseout="this.style.textDecoration='none'">talk to an agent</span>.
            </p>
        </div>

        <div class="row g-4">
            <!-- Service Card 1 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-img-container" style="height:200px; overflow:hidden;">
                        <img src="assets/images/insurance-image.jpg" class="card-img-top w-100 h-100 object-fit-cover" alt="What is Insurance?">
                    </div>
                    <div class="card-body d-flex flex-column bg-white">
                        <h5 class="card-title fw-bold" style="color: #1b75bb;">What is Insurance?</h5>
                        <p class="card-text text-muted flex-grow-1">Insurance is a contract or device to protect individual or business resources from the possibility.</p>
                        <a href="#contact" class="btn btn-sm mt-3 align-self-start" style="background-color: #168a49; color: #ffffff; border-radius: 25px;">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-img-container" style="height:200px; overflow:hidden;">
                        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=2070&q=80" class="card-img-top w-100 h-100 object-fit-cover" alt="Client Support">
                    </div>
                    <div class="card-body d-flex flex-column bg-white">
                        <h5 class="card-title fw-bold" style="color: #1b75bb;">We are here for our Clients</h5>
                        <p class="card-text text-muted flex-grow-1">Our commitment to excellence extends beyond our products and services. We believe that superior client service is the cornerstone of long-lasting relationships.</p>
                        <a href="#contact" class="btn btn-md mt-3 align-self-start" style="background-color: #168a49; color: #ffffff; border-radius: 25px;">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-md">
                    <div class="card-img-container" style="height:200px; overflow:hidden;">
                        <img src="assets/images/individual.png" class="card-img-top w-100 h-100 object-fit-cover" alt="Individualized Needs">
                    </div>
                    <div class="card-body d-flex flex-column bg-white">
                        <h5 class="card-title fw-bold" style="color: #1b75bb;">Individualized Needs</h5>
                        <p class="card-text text-muted flex-grow-1">Insurance needs vary greatly from person to person. At Streamlined Stays Group, we're dedicated to partnering with you to find the right insurance solution that balances affordability and comprehensive protection.</p>
                        <a href="#contact" class="btn btn-md mt-3 align-self-start" style="background-color: #168a49; color: #ffffff; border-radius: 25px;">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Service Card 4 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-img-container" style="height:200px; overflow:hidden;">
                        <img src="https://images.unsplash.com/photo-1605152276897-4f618f831968?auto=format&fit=crop&w=2070&q=80" class="card-img-top w-100 h-100 object-fit-cover" alt="Experience">
                    </div>
                    <div class="card-body d-flex flex-column bg-white">
                        <h5 class="card-title fw-bold" style="color: #1b75bb;">20+ Years of Experience</h5>
                        <p class="card-text text-muted flex-grow-1">With over two decades of experience in the insurance industry, Streamlined Stays Group has built a reputation for trust, reliability, and personalized service to protect what matters most to you.</p>
                        <a href="#contact" class="btn btn-md mt-3 align-self-start" style="background-color: #168a49; color: #ffffff; border-radius: 25px;">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>


<!-- FAQ Section -->
<section class="py-5" style="font-family: 'Montserrat', sans-serif;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: #1b75bb;">FREQUENTLY ASKED QUESTIONS</h2>
        </div>

        <div class="accordion" id="faqAccordion">
            <div class="row">
                <div class="col-md-6">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading1">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                WHAT IS HOME INSURANCE, AND WHY DO I NEED IT?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Home insurance provides financial protection against loss or damage to your home and belongings due to events like fire, theft, or natural disasters. It can also protect you from liability if someone is injured on your property.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading2">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                WHAT DOES MY HOME INSURANCE POLICY COVER?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                A typical home insurance policy covers your home structure, personal belongings, liability protection, and additional living expenses if you're temporarily unable to live in your home. However, coverage varies, so it's essential to review your specific policy.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading3">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                HOW MUCH HOME INSURANCE COVERAGE DO I NEED?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You should have enough coverage to rebuild your home in case of total loss and replace your personal belongings. Additional coverage might be needed based on factors like location or valuable items.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading4">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                DOES HOME INSURANCE COVER FLOOD DAMAGE?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Most standard home insurance policies do not cover flood damage. You may need to purchase a separate flood insurance policy to protect your home against flood-related damage.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- FAQ Item 5 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading5">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                HOW DO I FILE AN INSURANCE CLAIM?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To file a claim, contact your insurance company or agent as soon as possible after an incident. Provide all necessary information, such as the date, details of the event, and any supporting documentation (photos, receipts, etc.). Your insurer will guide you through the next steps.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading6">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                WHAT INFORMATION DO I NEED TO FILE A CLAIM?
                            </button>
                        </h2>
                        <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Typically, you will need details such as the date and description of the incident, a police report (if applicable), photos of the damage, receipts for any repairs or replacements, and your policy number.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading7">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                HOW LONG DO I HAVE TO FILE AN INSURANCE CLAIM?
                            </button>
                        </h2>
                        <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                The timeframe to file a claim varies depending on the type of insurance and the event. It's best to file as soon as possible after the incident to ensure you meet any deadlines set by your policy.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="heading8">
                            <button class="accordion-button collapsed bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                WHAT HAPPENS AFTER I FILE A CLAIM?
                            </button>
                        </h2>
                        <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Once you file a claim, an adjuster may be assigned to investigate the incident. They will assess the damage or situation, review your coverage, and determine the amount you'll be reimbursed based on your policy.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="d-flex justify-content-center mt-4">
            <a href="all-questions.html" class="btn btn-lg" style=" background-color: #39afda; color: #ffff; border-radius: 25px; font-weight: 600;">View All FAQ's</a>
        </div>
    </div>
</section>

<style>
    .accordion-button {
        border: none !important; /* Remove border */
        box-shadow: none !important; /* Remove default shadow */
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
    }
    .accordion-body {
        font-family: 'Montserrat', sans-serif;
    }
</style>


    <!-- Google Reviews Section -->
    <section id="google-reviews" style="padding: 40px 20px; font-family: 'Montserrat', sans-serif;">
        <h2 style="font-size: 2.6rem; margin-bottom: 30px; position: relative; padding-bottom: 15px; color: #0B47A8; font-weight: 800; text-align: center; text-transform: uppercase; font-family: 'Commuters Sans', sans-serif;">WHAT OUR BELOVED CLIENT SAYS</h2>
        <!-- Paste widget here -->
        <div class="elfsight-app-85bcf165-1287-45fe-889e-07996c45384e" data-elfsight-app-lazy></div>
    </section>


    <!-- Footer -->
    <?php include 'footer.php';?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    <!-- Elfsight Widget -->
    <script src="https://apps.elfsight.com/p/platform.js" defer></script>
    
</body>
</html>