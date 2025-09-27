<!-- Compact Footer -->
<footer class="footer-compact">
    <div class="footer-content">
        <!-- Contact -->
        <div class="contact">
            <p><i class="fas fa-phone"></i> <a href="tel:+18778732699">+(877) 873-2699</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:housing@streamlinedstays.com">housing@FurnishedUnfurnished.com</a></p>
        </div>

        <!-- Social Links -->
        <div class="social">
            <a href="https://www.instagram.com/streamlined.stays/" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/company/streamlined-stay-solutions/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        &copy; 2025 Furnished Unfurnished
    </div>
</footer>

<style>
/* ===== FIXED FOOTER STYLES ===== */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    position: relative;
}

/* Main content wrapper - this pushes footer down */
.main-content {
    flex: 1 0 auto;
    width: 100%;
}

/* Footer stays at bottom */
.footer-compact {
    background: #00524e;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    padding: 15px 20px;
    text-align: center;
    font-size: 14px;
    flex-shrink: 0;
    width: 100%;
    margin-top: auto; /* Push to bottom */
}

.footer-compact a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-compact a:hover {
    color: #ff7a59;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.footer-content .contact p {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.footer-content .social a {
    margin: 0 5px;
    font-size: 18px;
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 5px;
    font-size: 13px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .footer-content .contact {
        transform: translateX(0) !important;
    }
    
    .footer-content .social {
        transform: translateX(0) !important;
    }
}

@media (max-width: 480px) {
    .footer-compact {
        padding: 15px 10px;
    }
    
    .footer-content .contact p {
        flex-direction: column;
        gap: 2px;
    }
}
</style>