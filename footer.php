<!-- Compact Footer -->
<footer class="footer-compact">
    <div class="footer-content">
        <!-- Contact -->
        <div class="contact">
            <p><i class="fas fa-phone"></i> <a href="tel:+18778732699">+(877) 873-2699</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:housing@streamlinedstays.com">housing@FurnishedUnfurnished.com</a></p>
        </div>

        <!-- Logos 
        <div class="logos">
            <img src="images/corporate_housing_providers_association_RGB.png" alt="Logo 1">
            <img src="images/209-2092966_transparent-sdvosb-logo-png-service-disabled-veteran-owned.png" alt="Logo 2">
        </div>
-->
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
/* Compact Footer */
.footer-compact {
    background: #00524e;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    padding: 15px 20px;
    text-align: center;
    font-size: 14px;
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
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-bottom: 10px;
}

.footer-content .contact {
   transform: translateX(-350px);
}

.footer-content .social { transform: translateX(330px);}
.footer-content .contact p {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.footer-content .logos {
    display: flex;
    gap: 15px;
    align-items: center;
    justify-content: center;
}

.footer-content .logos img {
    height: 40px;
    width: auto;
    object-fit: contain;
}

.footer-content .social a {
    margin: 0 5px;
    font-size: 18px;
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 5px;
    font-size: 13px;
}

/* Mobile */
@media (max-width: 480px) {
    .footer-content {
        flex-direction: column;
        gap: 10px;
    }
    .footer-content .logos img {
        height: 35px;
    }
}
</style>
