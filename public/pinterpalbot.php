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

<br><br><br>
<div class="image-row">
  <div class="image-container2">
    <p class="image-text">1. Open</p>
        <img src="img/2025-08-07-1.png" alt="Animatie 1" class="gif-image">
    <img src="img/open-w.png" alt="Proces 1" class="static-image">
  </div>
  <div class="image-container2">
    <p class="image-text">2. Interact</p>
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
      bring your assortiment to life.
    </h2>
    <br>

    
<br><br>

<!-- Afbeelding in plaats van Problem + Solution + bullets -->
<div style="text-align:center; margin: 40px 0;">
  <img src="/img/pinterpal-solves.png" 
       alt="PinterPal widget solves choice overload with guided selling" 
       style="max-width: 100%; height: auto;">
</div>

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

  <!-- ———————————————————— INFO-SECTIE (VERVAAGD) ———————————————————— -->
  <section class="info-section" data-locked="true" id="pinterpal-info">
    <!-- Introductie sectie -->
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

    <!-- overlay bovenop (niet vervaagd) -->
    <div class="lock-overlay" aria-hidden="true">
      🔒 Play the game to unlock
    </div>
  </section>

  <!-- ———————————————————— SPEL ———————————————————— -->
  <section id="unlock-game" class="pintergame" aria-label="Simpel klik-spel om te ontgrendelen">
    <div class="pintergame-card" role="application">
      <header class="pintergame-header">
        <h3 class="pintergame-title">🎯 Click to play</h3>
        <div class="pintergame-hud" aria-live="polite">
          <span>Score: <b id="pg-score">0</b></span>
          <span>⏱️ <b id="pg-time">20</b>s</span>
          <span>🎯 Goal: <b id="pg-goal">8</b></span>
        </div>
      </header>

      <div id="pg-board" class="pintergame-board" role="grid" aria-label="Klik op het icoon als het verschijnt"></div>

      <footer class="pintergame-footer">
        <button id="pg-start" class="pintergame-btn" type="button">Start</button>
        <p id="pg-msg" class="pintergame-msg" aria-live="polite"></p>
      </footer>
    </div>
  </section>

  <style>
    /* ——— Thema: vaste kleuren ——— */
    :root {
      --pg-primary:   #0a7082; /* teal */
      --pg-secondary: #ffc107; /* amber */
      --pg-tertiary:  #8c52ff; /* paars (accent) */
      --pg-danger:    #ff6b6b;
      --pg-text:      #ffc107;  /* donkere tekst op lichte vlakken */
      --pg-muted:     #0b1220;  
      --pg-surface:   #0a7082;
;
      --pg-border:    rgba(11, 18, 32, .12);
    }

    /* ——— Info-sectie blur/overlay ——— */
    .info-section { position: relative; }
    .info-section[data-locked="true"] .intro { filter: blur(8px); pointer-events:none; user-select:none; }
    .info-section .lock-overlay {
      position: absolute; inset: 0; display:grid; place-items:center; padding:1.5rem; text-align:center;
      background: linear-gradient(180deg, rgba(10,112,130,.10), rgba(255,193,7,.10));
      border: 2px dashed #0a7082; border-radius: 12px; font-weight: 700; color: #0a7082;
    }
    .info-section:not([data-locked="true"]) .lock-overlay { display:none; }

    /* ——— Spel layout ——— */
    .pintergame { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; color: var(--pg-text); }
    .pintergame-card { max-width: 560px; margin: 1.25rem auto; padding: 1rem; background: var(--pg-surface); border: 1px solid var(--pg-border); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.08); }
    .pintergame-header { display:flex; flex-direction:column; gap:.5rem; align-items:center; }
    .pintergame-title { margin: 0; font-size: 1.15rem; letter-spacing:.2px; }
    .pintergame-hud { display:flex; gap:1rem; align-items:center; color: var(--pg-muted); }
    .pintergame-hud b { color: var(--pg-text); }

    .pintergame-board {
      --cell: 110px;
      margin: 1rem auto; width: calc(var(--cell) * 3);
      display: grid; grid-template-columns: repeat(3, var(--cell)); gap: 10px; touch-action: manipulation;
    }
    @media (max-width: 480px) { .pintergame-board { --cell: 90px; width: calc(var(--cell) * 3); } }

    .pg-cell { position: relative; height: var(--cell); border-radius: 14px; background: #f4f6fb; border: 1px solid var(--pg-border); overflow: hidden; }

    .pg-target {
      position: absolute; inset: 8px; display: grid; place-items:center;
      background: linear-gradient(180deg, var(--pg-primary), var(--pg-secondary));
      color: #032a31; font-weight: 800; letter-spacing:.2px;
      border-radius: 12px; border: 0; cursor: pointer;
      box-shadow: 0 10px 18px rgba(10,112,130,.20), 0 2px 0 0 rgba(255,193,7,.25) inset;
      transition: transform .07s ease;
    }
    .pg-target:active { transform: scale(.96); }
    .pg-target[hidden] { display:none; }

    .pintergame-footer { display:flex; flex-direction: column; gap:.5rem; align-items:center; }
    .pintergame-btn {
      background: linear-gradient(180deg, var(--pg-primary), var(--pg-tertiary));
      color: white; border: none; padding: .6rem 1rem; font-weight: 800; border-radius: 12px; cursor: pointer;
      box-shadow: 0 6px 14px rgba(140,82,255,.35);
    }
    .pintergame-btn[disabled] { opacity:.65; cursor: not-allowed; }
    .pintergame-msg { min-height: 1.25rem; margin: .25rem 0 0; color: var(--pg-muted); text-align: center; }
    .pg-win { color: greenyellow; }
    .pg-lose { color: var(--pg-danger); }

    /* subtiele pulse bij score */
    .pg-pulse { animation: pgPulse .3s ease; }
    @keyframes pgPulse { from { transform: scale(1.12); } to { transform: scale(1); } }
  </style>

  <script>
  (function(){
    const DURATION = 20;   // seconden
    const GOAL = 8;        // punten nodig om te winnen
    const GRID = 9;        // 3x3
    const SPAWN_MS = 800;  // hoe vaak doel verplaatst

    const board = document.getElementById('pg-board');
    const startBtn = document.getElementById('pg-start');
    const scoreEl = document.getElementById('pg-score');
    const timeEl = document.getElementById('pg-time');
    const goalEl = document.getElementById('pg-goal');
    const msgEl = document.getElementById('pg-msg');
    goalEl.textContent = GOAL; timeEl.textContent = DURATION;

    // Bouw 3x3 cellen
    const cells = [];
    for (let i = 0; i < GRID; i++) {
      const cell = document.createElement('div');
      cell.className = 'pg-cell';
      cell.setAttribute('role', 'gridcell');
      board.appendChild(cell);
      cells.push(cell);
    }

// Doelknop (1 instantie, we verplaatsen 'm)
const target = document.createElement('button');
target.className = 'pg-target';
target.type = 'button';
target.setAttribute('aria-label', 'Klik');
target.hidden = true;

target.innerHTML = '<span class="pg-target-txt">Get PinterPal</span>';


// maak tekst compacter
target.style.fontSize = '10px';
target.style.lineHeight = '1.1';
target.style.letterSpacing = '0';
target.style.paddingInline = '6px';
target.style.whiteSpace = 'nowrap';
target.style.fontWeight = '700'; // <-- bold



    let score = 0, timeLeft = DURATION, timerId = null, spawnId = null, lastIndex = -1, running = false;

    function randIndex(){
      let idx = Math.floor(Math.random() * GRID);
      if (GRID > 1 && idx === lastIndex) idx = (idx + 1) % GRID; // vermijd dezelfde plek
      lastIndex = idx; return idx;
    }

    function spawn(){
      const idx = randIndex();
      cells[idx].appendChild(target);
      target.hidden = false;
    }

    function clearIntervals(){
      if (timerId) { clearInterval(timerId); timerId = null; }
      if (spawnId) { clearInterval(spawnId); spawnId = null; }
    }

    function endGame(win){
      running = false;
      clearIntervals();
      startBtn.disabled = false;
      target.hidden = true;
      msgEl.textContent = win ? '🎉 Well done! Section unlocked.' : 'Unlucky! Try again.';
      msgEl.className = 'pintergame-msg ' + (win ? 'pg-win' : 'pg-lose');

      if (win) {
        try {
          if (typeof window.unlockInfoSection === 'function') {
            window.unlockInfoSection();
          } else {
            const section = document.getElementById('pinterpal-info');
            if (section) section.removeAttribute('data-locked');
            window.dispatchEvent(new CustomEvent('pinterpal:unlocked'));
          }
        } catch (e) { /* stil falen */ }
      }
    }

    function start(){
      if (running) return;
      running = true;
      score = 0; timeLeft = DURATION;
      scoreEl.textContent = '0'; timeEl.textContent = String(DURATION);
      msgEl.textContent = ''; msgEl.className = 'pintergame-msg';
      startBtn.disabled = true;

      spawn();
      spawnId = setInterval(spawn, SPAWN_MS);

      timerId = setInterval(() => {
        timeLeft -= 1; timeEl.textContent = String(timeLeft);
        if (timeLeft <= 0) endGame(false);
      }, 1000);
    }

    target.addEventListener('click', () => {
      if (!running) return;
      score += 1; scoreEl.textContent = String(score);
      scoreEl.classList.remove('pg-pulse'); void scoreEl.offsetWidth; scoreEl.classList.add('pg-pulse');
      if (score >= GOAL) endGame(true);
    });

    startBtn.addEventListener('click', start);
    document.addEventListener('keydown', (e) => {
      if (!running || target.hidden) return;
      if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); target.click(); }
    });
  })();

  // Optioneel: exposeer functie die je elders kunt aanroepen
  window.unlockInfoSection = window.unlockInfoSection || function(){
    const section = document.getElementById('pinterpal-info');
    if (section) section.removeAttribute('data-locked');
  };
  </script>
    <!-- ———————————————————— EIND SPEL ———————————————————— -->


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

</body>
</html>
