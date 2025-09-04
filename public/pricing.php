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
    
<!-- Header -->
<header class="header">
  <div class="logo-wrap">
    <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    <a href="index.php" class="logo-boven">
      <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
    </a>
  </div>

 
  <!-- Dynamische login-/signup of uitlog-knoppen -->
  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>

    <!-- Navigatiebalk -->
    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="pinterpalbot.php" class="active">PINTERPAL BOT</a>
        <a href="iframe.php">TRY ME</a>
        <a href="pricing.php">PRICING</a>
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
  <h3>Personal Assistance</h3>
  <p>We train the PinterPal bot on your product database for a fully customized experience.</p>
  <p><s style="color:red;">€1000</s> → <strong>€799 one-time setup</strong></p>
  <br>
  <button class="start-trial-btn" onclick="window.location.href='contact.php'">Get Started</button>
</div>

  </section>
</section>
</main>


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
