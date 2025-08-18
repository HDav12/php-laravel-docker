<?php
// Start de sessie voor toegang tot gebruikersinformatie
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Assistance - PinterPal</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* ====== Mobile-first fixes specifiek voor deze pagina ====== */

    :root { --gap: 1rem; --maxw: 1100px; }

    /* Zorg voor nette basis op mobiel */
    body { margin:0; font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif; color:#111; }

    /* Header */
    .header { padding: .75rem var(--gap) 0; text-align:center; }
    .logo-boven img { max-width: 220px; width: 60vw; height:auto; margin: 0 auto; display:block; }
    .subtitle { margin:.25rem 0 0; font-size:.95rem; color:#555; }
    .login-signup { display:flex; align-items:center; justify-content:center; gap:.75rem; flex-wrap:wrap; margin:.5rem 0 0; }
    .header-logo { max-width: 56px; height:auto; }

    /* NAV */
    .navbar { position:sticky; top:0; z-index:10; display:flex; gap:.25rem; flex-wrap:wrap; justify-content:center; padding:.5rem; border-top:4px solid var(--secondary-color); border-bottom:4px solid var(--secondary-color); background:var(--primary-color); }
    .navbar a { display:inline-block; padding:.5rem .75rem; border-radius:.5rem; text-decoration:none; font-weight:600; }
    .navbar a:hover, .navbar a.active { background:rgba(255,255,255,.12); color: var(--font-color); }

    /* Content wrapper */
    .content { max-width: var(--maxw); margin: 0 auto; padding: 1rem var(--gap) 2rem; }

    .intro3 { text-align:center; }
    .intro3 h2 { font-size: clamp(1.25rem, 4.5vw, 2rem); margin:.5rem 0; }
    .intro3 p { color:#333; margin:.5rem auto 0; max-width: 70ch; }

    /* Assistance grid */
    .assistance {
      display:grid;
      grid-template-columns: 1fr;
      gap: var(--gap);
      margin: 1.25rem 0 0;
      padding: 1rem;
      background-color: rgba(10,112,130,.05); /* zachter op mobiel */
      border-radius: .75rem;
    }
    .assistance .option {
      text-align:center;
      background: var(--secondary-color);
      border-radius:.75rem;
      padding: 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,.12);
    }
    .assistance .option h3 { margin:.25rem 0 .5rem; font-size:1.125rem; }
    .assistance .option p { margin:0; }
    .no-glow-btn {
      display:inline-block;
      margin-top:.75rem;
      padding:.65rem .9rem;
      border-radius:.65rem;
      border:1px solid rgba(0,0,0,.1);
      background:#fff;
      color:#111;
      cursor:pointer;
    }

    /* GIF */
    .gif-container { margin: 1.25rem auto 0; max-width: 480px; }
    .gif-container img { width:100%; height:auto; display:block; border-radius:.75rem; }

    /* Footer */
    .contact-info { margin-top: 2rem; }

    /* Breakpoints */
    @media (min-width: 640px) {
      .assistance { grid-template-columns: repeat(2, minmax(0,1fr)); padding: 1.25rem; }
    }
    @media (min-width: 1024px) {
      .assistance { grid-template-columns: repeat(3, minmax(0,1fr)); }
    }
  </style>
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

  <p class="subtitle">at your service</p>

  <!-- Dynamische login-/signup of uitlog-knoppen -->
  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>

  <!-- Navigation -->
  <nav class="navbar" aria-label="Hoofdnavigatie">
    <a href="index.php">HOME</a>
    <a href="pinterpalbot.php">PINTERPAL BOT</a>
    <a href="iframe.php">DEMO</a>
    <a href="pricing.php">PRICING</a>
    <a href="assistance.php" class="active" aria-current="page">ASSISTANCE</a>
    <a href="about.php">ABOUT US</a>
  </nav>
  
  <!-- Content -->
  <main class="content">
    <!-- Assistance Intro Section -->
    <section class="intro3">
      <h2>How Can We Assist You?</h2>
      <p>
        Welcome to the Assistance page! Here, you'll find helpful resources, FAQs,
        and contact options to ensure you get the most out of PinterPal.
        Our goal is to make your experience as seamless as possible.
        Let us know how we can support you!
      </p>
    </section>

    <!-- Assistance Options -->
    <section class="assistance">
      <div class="option">
        <h3>Contact Support</h3>
        <p>Need further help? Reach out to our support team directly for personalized assistance.</p>
        <button class="no-glow-btn" onclick="window.location.href='contact.php'">Contact Us</button>
      </div>
      <div class="option">
        <h3>Frequently Asked Questions</h3>
        <p>Find quick answers to the most common queries about PinterPal.</p>
        <button class="no-glow-btn" onclick="window.location.href='faqs.php'">View FAQs</button>
      </div>
      <div class="option">
        <h3>User Guide</h3>
        <p>Learn how to integrate and maximize PinterPal with our step-by-step guide.</p>
        <button class="no-glow-btn" onclick="window.location.href='guide.php'">View Guide</button>
      </div>
    </section>

    <!-- Fun GIF -->
    <div class="gif-container">
      <img
        src="https://media1.tenor.com/m/r36NoI3ZTZIAAAAd/ok.gif"
        alt="Salute GIF"
        loading="lazy"
      />
    </div>
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
      // Active link highlighting
      const currentPage = window.location.pathname.split('/').pop() || 'index.php';
      document.querySelectorAll('.navbar a').forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === currentPage);
      });
    });
  </script>
</body>
</html>
