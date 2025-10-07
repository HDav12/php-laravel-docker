<?php
// Start de sessie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    

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
  <a href="index.php">HOME</a>
  <a href="pinterpalbot.php">PINTERPAL BOT</a>
  <a href="iframe.php">TRY ME</a>
  <a href="pricing.php" class="active">PRICING</a>
  <a href="assistance.php">ASSISTANCE</a>
  <a href="about.php">ABOUT US</a>
</nav>

    
    
    <!-- Content -->
<main class="content">
    <!-- Pricing Intro Section -->
    <section class="intro3">
        <h2>Take your e-commerce store to the next level</h2>

        <p>Currently working on adittional plans...</p>
     
    </section>


        <div class="gif-container">
  <img
    src="https://i0.wp.com/sifugadget.com/wp-content/uploads/2024/02/Arrows-3-pointing-down-arrow-down-animated.gif?ssl=1"
    alt="Salute GIF"
  />
</div>


  <!-- Pricing Options Section -->
<section class="pricing-options">
  <section class="assistance">

    <!-- PinterPal block -->
    <div class="option">
  <h3>PinterPal</h3>
  <p>Take your webshop to the next level.</p>
  <p><s style="color:red;">€49.90</s> → <strong>€29.90 per month</strong></p>
  <br>
  <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">Start Now</button>
</div>

<!-- Personal Assistance block -->
<div class="option">
  <h3>Train the PinerPal bot on your database</h3>
  <p>We train the PinterPal bot on your product database for a fully customized experience.</p>
  <p><s style="color:red;">€999</s> → <strong>€799 one-time setup</strong></p>
  <br>
  <button class="start-trial-btn" onclick="window.location.href='contact.php'">Get Started</button>
</div>

  </section>
</section>
</main>

<div id="contactPopup" class="popup">
  <span id="popupClose">&times;</span>

  <img src="img/about-hidde.png" alt="Hidde" class="popup-photo">
  <h3>Interested or questions?</h3>
  <p>Call me or mail me:</p>

  <a href="tel:+31636270282" class="popup-btn">+31636270282</a>
  <a href="mailto:davids@pinterpal.com" class="popup-btn">Mail Hidde</a>

  <!-- NEW: plan demo -->
  <a href="/demo.php" class="popup-btn popup-btn-primary">Plan free demo</a>
</div>

<script src="js/contact-popup.js" defer></script>


      <!-- Footer -->
  <footer class="contact-info">
    <p>
      <strong>KVK:</strong> 96433647<br>
      <strong>Address:</strong> Den Haag<br>
      <strong>Telephone:</strong> +31 6 36270282<br>
      <strong>Email:</strong> info@pinterpal.com
    </p>
    <p>
      <a href="/terms-coditions.php" style="color: inherit; text-decoration: none;">
        📄 Terms &amp; Conditions
      </a>
    </p>
  </footer>

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
