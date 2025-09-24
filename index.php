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
<?php include 'nav-mobile.php'; ?>
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
  color: #fff;
  font-size: 1.2rem;
  font-weight: 600;
  text-transform: uppercase;
  background: rgba(0,0,0,0.5);
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