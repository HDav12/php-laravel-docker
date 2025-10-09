<?php
// Start sessie (voor toegang tot $_SESSION['user_logged_in'])
require __DIR__.'/bootstrap.php';;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PinterPal — Try the Widget</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Kleine iframe-pagina-specifieke layout */
    .iframe-wrap{
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 16px;
    }
    .iframe-box{
      width: 100%;
      aspect-ratio: 9 / 16;          /* mobiel-achtig canvas */
      max-height: 85vh;
      border: 2px solid var(--secondary-color);
      border-radius: 14px;
      box-shadow: 0 10px 24px rgba(0,0,0,.25);
      overflow: hidden;
      background: #fff;
    }
    .iframe-box iframe{
      width: 100%;
      height: 100%;
      border: 0;
      display: block;
    }

    /* Op desktop iets breder dan phone-ratio */
    @media (min-width: 900px){
      .iframe-box{ aspect-ratio: 16 / 9; }
    }
  </style>
</head>
<body class="iframe-page">

<!-- Header (zelfde als index.php) -->
<header class="header">
  <div class="logo-wrap">
    <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    <a href="index.php" class="logo-boven">
      <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
    </a>
  </div>

  <div class="header-cta">
    <button class="start-trial-btn" onclick="window.location.href='/company-registration.php'">Start Now</button>
  </div>

  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>

  <!-- Hamburger voor mobiel: helemaal rechts -->
  <button class="nav-toggle" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
  </button>
</header>

<!-- Navigatiebalk (zelfde items, zelfde ids/classes) -->
<nav id="mainNav" class="navbar" aria-label="Hoofdnavigatie">
  <a href="index.php">HOME</a>
  <a href="pinterpalbot.php">PINTERPAL BOT</a>
  <a href="iframe.php" class="active">TRY ME</a>
  <a href="pricing.php">PRICING</a>
  <a href="assistance.php">ASSISTANCE</a>
  <a href="about.php">ABOUT US</a>
</nav>

<!-- Content -->
<div class="content">
  <section class="intro4">
    <div class="intro-flex" style="justify-content:center;">
      <h2>Try the PinterPal Guided Selling Widget live ↓</h2>
    </div>

    <div class="iframe-wrap">
      <div class="iframe-box">
        <!-- Gebruik de werkende link -->
        <iframe
          src="https://widget-2-0.onrender.com/"
          title="PinterPal Demo Widget"
          allow="clipboard-write; fullscreen; accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

    <!-- CTA's onder de iframe (optioneel, zoals index.php) -->
    <div class="intro-text" style="padding: 24px; text-align: center; width: 100%;">
      <button class="start-trial-btn"
              onclick="window.location.href='/company-registration.php'"
              style="font-size:18px; padding:12px 24px; margin-top:12px;">
        Start Now
      </button>
      <button class="start-trial-btn alt"
              onclick="window.location.href='/demo.php'"
              style="font-size:18px; padding:12px 24px; margin-top:12px; margin-left:12px;">
        Plan Free Demo
      </button>
    </div>
  </section>
</div>

<!-- (optioneel) dezelfde feedback/news/onepager-sectie zou je hier ook kunnen herhalen -->

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

<!-- Active link + hamburger toggle (zelfde scripts als index.php) -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const currentPage = window.location.pathname.split('/').pop();
    const navbarLinks = document.querySelectorAll('.navbar a');
    navbarLinks.forEach(link => link.classList.remove('active'));
    navbarLinks.forEach(link => {
      const linkPage = link.getAttribute('href');
      if (linkPage === currentPage || (linkPage === "iframe.php" && currentPage === "")) {
        link.classList.add('active');
      }
    });
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navToggle = document.querySelector(".nav-toggle");
    const nav = document.getElementById("mainNav");
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
