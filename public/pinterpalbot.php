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
  
<!-- Header -->
<header class="header">
  <div class="logo-wrap">
    <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    <a href="index.php" class="logo-boven">
      <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
    </a>
  </div>

  <p class="subtitle">PinterPal bot</p>

  <!-- Dynamische login-/signup of uitlog-knoppen -->
  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>

    <!-- Navigatiebalk -->
   <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="pinterpalbot.php" class="active">PINTERPAL BOT</a>
        <a href="iframe.php">DEMO</a>
        <a href="pricing.php">PRICING</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="about.php">ABOUT US</a>
    </nav>

<br><br><br>
<div class="image-row">
  <div class="image-container2">
    <p class="image-text">1. Open Widget</p>
        <img src="img/2025-08-07-1.png" alt="Animatie 1" class="gif-image">
    <img src="img/open-w.png" alt="Proces 1" class="static-image">
  </div>
  <div class="image-container2">
    <p class="image-text">2. Share preferences with PinterPal</p>
        <img src="img/2025-08-07-2.png" alt="Animatie 2" class="gif-image">
    <img src="img/input-w.png" alt="Proces 2" class="static-image">
  </div>
  <div class="image-container2">
    <p class="image-text">3. Result</p>
        <img src="img/2025-08-07-3.png" alt="Animatie 3" class="gif-image">
    <img src="img/result-w.png" alt="Result" class="static-image">
  </div>
</div>

<div class="content">
  <div class="intro">

    <h2>
      Make every webshop feel like a helpful shop assistant —
      asking the right questions.
    </h2>
    <br>

    <p class="lead">
      Stop scrolling. Start finding.<br>
      Guided selling powered by your existing product data — zero extra maintenance.
    </p>

    <br><br>

    <p><strong style="font-size:24px;">The Problem:</strong><br><br>
      Choice overload kills conversion. Visitors bounce because search is generic and filters feel like work.
    </p>

    <br>

    <p><strong style="font-size:24px;">The Solution:</strong><br><br>
      PinterPal guides shoppers through 4–8 dynamic questions and instantly ranks the best-fit products —
      with clear reasons they can trust.
    </p>

    <br>

    <ul>
      <li><strong>Boost conversion</strong> with real-time, personalized recommendations.</li>
      <li><strong>Reduce drop-off</strong> by making shopping simple and intuitive.</li>
      <li><strong>Cut support load</strong> — shoppers choose confidently on their own.</li>
      <li><strong>Plug &amp; play</strong> — API-ready for Shopify, WooCommerce, and custom stacks.</li>
    </ul>

    <br>

    <p>GDPR-first · No PII stored · EU hosting options</p>

    <br>

    <p><strong>Ready to level up your webshop?</strong><br>
       Click below and try it for yourself!
    </p>

    <br>
        <!-- Start-knop -->
        <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">
            Start Now
        </button>
    </div>
</div>




          </div>


 <!-- Content sectie -->
<main class="content">
    <!-- Flexbox sectie: Introductie en Pricing -->
    <section class="info-section">
        <!-- Introductie sectie -->
        <div class="intro">
            <h2>What Is the PinterPal Bot?</h2>
            <br>
            <p>
                PinterPal is a smart widget that helps your webshop visitors find their ideal product — fast. With a short, personalized questionnaire, the bot guides shoppers step-by-step to the product that fits their exact needs.
            </p>
            <br>
            <p>
                Whether they’re looking for a vacuum cleaner or a vacation, etc. The bot asks smart, dynamic questions based on your product range. For example: cordless or not? Budget-friendly? Silent? Thanks to AI, each shopper gets tailored suggestions that match their preferences.
            </p>
            <br>
            <p>
                The best part? The questions are auto-generated using your own product data — descriptions, specs, and details already on your site. No extra input needed from your side.
            </p>
            <br>
            <p>
                Seamlessly integrated via API and offered as a monthly subscription, PinterPal delivers a smoother shopping experience, reduces support questions, and increases conversion — across any product category.
            </p>
        </div>


        <!-- Pricing Info sectie -->
<div class="pricing-info">
    <h3>START NOW, TAKE YOUR WEBSITE TO THE NEXT LEVEL</h3>
    <br>
    <div class="start-trial-container">
        <img src="img/pinterpal-start-trial.jpg" alt="Start trial icon" class="start-trial-img">
        <div class="start-trial-text-button">
            <p>€ 29,90 Per month</p>
            <br>
            <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">Start Now</button>
        </div>
    </div>
</div>

        </section>
    </main>

 

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

      <!-- Widget Pop‑Up -->
  <div id="widgetContainer" class="widget-toggle"></div>

  <!-- Widget JS -->
<script src="js/widget.js"></script>

</body>
</html>
