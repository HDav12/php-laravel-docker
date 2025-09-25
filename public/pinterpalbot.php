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

  <!-- Hamburger voor mobiel -->
  <button class="nav-toggle" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
  </button>

  <div class="header-cta">
    <button class="start-trial-btn" onclick="window.location.href='/company-registration.php'">Start Now</button>
  </div>

  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>

<!-- Navigatiebalk -->
<nav id="mainNav" class="navbar" aria-label="Hoofdnavigatie">
  <a href="index.php" class="active">HOME</a>
  <a href="pinterpalbot.php">PINTERPAL BOT</a>
  <a href="iframe.php">TRY ME</a>
  <a href="pricing.php">PRICING</a>
  <a href="assistance.php">ASSISTANCE</a>
  <a href="about.php">ABOUT US</a>
</nav>

<br><br><br>
<div class="image-row">
  <div class="image-container2">
    <p class="image-text">1. Open</p>
    <img src="img/open-w.png" alt="Proces 1" class="static-image">
  </div>
  <div class="image-container2">
    <p class="image-text">2. Interact</p>
    <img src="img/input-w.png" alt="Proces 2" class="static-image">
  </div>
  <div class="image-container2">
    <p class="image-text">3. Result</p>
    <img src="img/result-w.png" alt="Result" class="static-image">
  </div>
</div>

<div class="content">
  <div class="intro">

    <h2>
      Make every webshop feel like a helpful shop assistant —
      bring your assortiment to life.
    </h2>
    <br>

    
<br><br>

<!-- Afbeelding in plaats van Problem + Solution + bullets -->
<div style="text-align:center; margin: 40px 0;">
  <img src="img/pp-solves3.png" 
       alt="PinterPal widget solves choice overload with guided selling" 
       style="max-width: 100%; height: auto;">
</div>

    <p>No technical skills needed</p>

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

  <!-- ———————————————————— INFO-SECTIE (VERVAAGD) ———————————————————— -->
  <section class="info-section" data-locked="true" id="pinterpal-info">
  <!-- Value Proposition / Hero -->
  <div class="value-prop" id="pinterpal-hero">

  

  <!-- Introductie sectie (bestaande content) -->
  <div class="intro" id="pinterpal-copy">
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

   <!-- Footer -->
 <footer class="contact-info" style="text-align:center;">
    <p>
      <strong>KVK:</strong> 96433647 · <strong>Den Haag</strong><br>
      <strong>Tel:</strong> +31 6 36270282 · <strong>Email:</strong> info@pinterpal.com
    </p>
    <p>
      <a href="/terms-coditions.php" style="color: inherit; text-decoration: none;">📄 Terms &amp; Conditions</a>
    </p>
  </footer>

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
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navToggle = document.querySelector(".nav-toggle");
    const nav = document.getElementById("mainNav");

    // optioneel: markeer body zodat CSS weet dat er een hamburger is
    document.body.classList.add("has-hamburger");

    if (navToggle && nav) {
      navToggle.addEventListener("click", () => {
        const expanded = navToggle.getAttribute("aria-expanded") === "true";
        navToggle.setAttribute("aria-expanded", String(!expanded));
        nav.classList.toggle("open");
      });
    }
  });
</script>

</body>
</html>
