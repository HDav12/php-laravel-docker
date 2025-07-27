<?php
// Start de sessie om toegang te hebben tot gebruikersinformatie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinterPal Bot Explained</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <p class="subtitle">PinterPal Bot</p>
        <div class="login-signup">
            <!-- Dynamische login/signup of uitlog-knoppen -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <!-- Navigatiebalk -->
    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="iframe.php">DEMO</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="pricing.php">PRICING</a>
        <a href="about.php">ABOUT US</a>
        <a href="pinterpalbot.php" class="active">PINTERPAL BOT</a>
    </nav>

<div class="content">
        <div class="intro">
            <h2>"PinterPal: Your go-to assistant for understanding online shoppers' wants and needs, offering expert assistance across almost all categories</h2>
            <p>___________________________________________________________________________________________________________________________________________________________</p>
            <p>
            <strong style="font-size: 24px;">The Problem:</strong><br><br>
                Webshop owners face the challenge of converting visitors into customers. Many shoppers get overwhelmed by too many choices...
            </p>
            <br>
            <p>
            <strong style="font-size: 24px;">The Solution:</strong><br><br>
            PinterPal is your smart assistant, designed to guide customers effortlessly through their shopping journey:
            </p>
            <ul><br>
                <li><strong>Personalize the experience</strong> with tailored product recommendations.</li>
                <li><strong>Boost conversions</strong> by making it easier for customers to find what they need.</li>
                <li><strong>Save time</strong> by automating product suggestions based on customer input.</li>
            </ul>
            <br>
            <p>Seamlessly integrated into your webshop, PinterPal turns indecisive visitors into loyal buyers...</p>
            <p><strong>Take your webshop to the new Era.</strong></p>
            <br>
            <p>Push the button and upgrade your website! The first month is on the house!</p>
            <p>___________________________________________________________________________________________________________________________________________________________</p>

            <!-- Start-knop -->
            <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">
                Start Now
            </button>
        </div>


    <!-- Content sectie -->
    <main class="content">
        <!-- Flexbox sectie: Introductie en Pricing -->
        <section class="info-section">
            <!-- Introductie sectie -->
            <div class="intro">
                <h2>PinterPal Bot Explained</h2>
                <br>
                <p>
        Our product is an intelligent widget designed to help <strong>website visitors</strong> find the perfect product match through a personalized questionnaire. This widget, which can be integrated into wenshops via an API (offered as a monthly subscription), guides <strong>website visitors</strong> step-by-step through a tailored set of questions about their specific needs and preferences.
    </p>
<br>
    <p>
        The questionnaire, typically between 5-10 questions, adapts dynamically based on each <strong>website visitor’s</strong> responses, leading them to the product(s) that best meet their criteria. For instance, if a <strong>website visitor</strong> is searching for a vacuum cleaner, the widget will ask targeted questions—such as whether they prefer a cordless model, quiet operation, bagless design, affordability, and more. By understanding these preferences, the widget narrows down the options to present the best-suited products.
    </p>

    <p>
        Our AI-driven system generates these questions using data from the webshop itself, including product descriptions, specifications, and other relevant details that can be found on the website. This makes the experience highly customized, with each survey tailored to the unique offerings and inventory of the business.
    </p>
<br>
    <p>
        Whether it’s electronics, home goods, vacations, or other categories, the widget provides a seamless, guided shopping experience that benefits both the <strong>website visitor</strong> and the retailer.
    </p>
            </div>

        <!-- Pricing Info sectie -->
<div class="pricing-info">
    <h3>START NOW, GET ONE MONTH FOR FREE</h3>
    <br>
    <div class="start-trial-container">
        <img src="img/pinterpal-start-trial.jpg" alt="Start trial icon" class="start-trial-img">
        <div class="start-trial-text-button">
            <p>€ 29,99 Per month</p>
            <br>
            <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">Start Now</button>
        </div>
    </div>
</div>

        </section>
    </main>

    <!-- Process sectie -->
    <section class="process">
       
        <div class="process-image">
            <img src="img/pinterpal-roadmap.jpg" alt="The PinterPal Process">
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the current page from the URL (only the last part)
            const currentPage = window.location.pathname.split('/').pop();
    
            // Select all links in the navigation bar
            const navbarLinks = document.querySelectorAll('.navbar a');
    
            // Remove 'active' class from all links initially
            navbarLinks.forEach(link => link.classList.remove('active'));
    
            // Add 'active' class to the correct link based on the href match
            navbarLinks.forEach(link => {
                const linkPage = link.getAttribute('href');
                if (linkPage === currentPage || (linkPage === "index.php" && currentPage === "")) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
