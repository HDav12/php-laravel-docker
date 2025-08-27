<?php
// Start de sessie voor toegang tot gebruikersinformatie
require __DIR__.'/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Coming Soon — PinterPal</title>
  <link rel="stylesheet" href="css/style.css" />

  <style>
    /* ====== Mobile-first styling specifiek voor Coming Soon ====== */
    :root{
      --gap: 1rem;
      --maxw: 1100px;
      --radius: 16px;
      --shadow: 0 12px 28px rgba(0,0,0,.18);
      --teal: #0a7082;
    }

    body{ margin:0; font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif; color:#111; }

    /* Header */
    .header{ padding:.75rem var(--gap) 0; text-align:center; }
    .logo-boven img{ max-width:220px; width:60vw; height:auto; margin:0 auto; display:block; }
    .login-signup{ display:flex; align-items:center; justify-content:center; gap:.75rem; flex-wrap:wrap; margin:.5rem 0 0; }
    .header-logo{ max-width:56px; height:auto; }

    /* NAV */
    .navbar{
      position:sticky; top:0; z-index:10; display:flex; gap:.25rem; flex-wrap:wrap; justify-content:center;
      padding:.5rem; border-top:4px solid var(--secondary-color); border-bottom:4px solid var(--secondary-color);
      background:var(--primary-color);
    }
    .navbar a{ display:inline-block; padding:.5rem .75rem; border-radius:.5rem; text-decoration:none; font-weight:600; }
    .navbar a:hover, .navbar a.active{ background:rgba(255,255,255,.12); color:var(--font-color); }

    /* Content */
    .content{ max-width:var(--maxw); margin:0 auto; padding:1rem var(--gap) 2.25rem; }

    /* Hero */
    .cs-hero{
      text-align:center;
      background: linear-gradient(0deg, rgba(10,112,130,.08), rgba(10,112,130,.08));
      border-radius: var(--radius);
      padding: clamp(1rem,3.5vw,2rem);
      box-shadow: 0 1px 2px rgba(0,0,0,.06) inset;
      border: 2px solid rgba(10,112,130,.2);
    }
    .cs-badge{
      display:inline-flex; align-items:center; gap:.5rem;
      background: var(--secondary-color);
      border: 2px solid var(--teal);
      color:#111;
      padding:.35rem .6rem; border-radius:999px; font-weight:700; letter-spacing:.3px; font-size:.9rem;
    }
    .cs-hero h1{
      font-size: clamp(1.5rem, 6vw, 2.5rem);
      margin:.6rem 0 .25rem;
      line-height:1.15;
    }
    .cs-hero p{ margin:.25rem auto 0; max-width: 70ch; color:#222; }

    /* Countdown kaart */
    .cs-countdown{
      margin: 1rem auto 0;
      background: var(--secondary-color);
      border-radius: var(--radius);
      padding: clamp(.9rem, 3.5vw, 1.25rem);
      box-shadow: var(--shadow);
      border: 3px solid var(--teal);
      max-width: 860px;
    }
    .cd-grid{
      display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:.75rem; align-items:stretch;
    }
    .cd-cell{
      background:#fff; border-radius:14px; padding:.85rem;
      display:grid; place-items:center; text-align:center; border:1px solid rgba(0,0,0,.08);
    }
    .cd-num{ font-weight:800; font-size: clamp(1.6rem, 7vw, 2.4rem); line-height:1; }
    .cd-label{ font-size:.85rem; opacity:.8; }

    /* Notify + CTA */
    .cs-actions{ display:grid; gap:.75rem; margin: 1rem auto 0; max-width:860px; }
    .notify{
      display:grid; grid-template-columns: 1fr; gap:.5rem;
      background: #fff; border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:.75rem;
    }
    .notify input[type="email"]{
      width:100%; padding:.75rem .9rem; border-radius:.65rem; border:1px solid rgba(0,0,0,.2); font-size:1rem;
    }
    .btn{
      display:inline-flex; justify-content:center; align-items:center; gap:.5rem;
      padding:.75rem 1rem; border-radius:.75rem; border:2px solid var(--teal);
      background: var(--secondary-color); color:#111; cursor:pointer; font-weight:800; text-decoration:none;
    }
    .btn:hover{ filter: saturate(1.05) brightness(1.03); }
    .btn.secondary{ background:#fff; }

    /* Info chips */
    .cs-chips{ display:flex; gap:.5rem; flex-wrap:wrap; justify-content:center; margin-top:.75rem; }
    .chip{
      border:1.5px solid var(--teal); background:#fff; color:#111; border-radius:999px; padding:.35rem .65rem; font-size:.9rem; font-weight:700;
    }

    /* Footer mini */
    .cs-footer{ text-align:center; margin-top:1.5rem; color:#333; }
    .sr-only{ position:absolute!important; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }

    /* Breakpoints */
    @media (min-width:640px){
      .notify{ grid-template-columns: 1fr auto; align-items:center; }
      .cd-grid{ grid-template-columns: repeat(4, minmax(0,1fr)); gap: .9rem; }
      .cs-actions{ grid-template-columns: 1fr auto; align-items:center; }
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

    <!-- Dynamische login-/signup of uitlog-knoppen -->
    <div class="login-signup">
      <?php include 'navbar.php'; ?>
    </div>
  </header>

  <!-- Navigation -->
  <nav class="navbar" aria-label="Hoofdnavigatie">
    <a href="index.php">HOME</a>
    <a href="pinterpalbot.php">PINTERPAL BOT</a>
    <a href="iframe.php">TRY ME</a>
    <a href="pricing.php">PRICING</a>
    <a href="assistance.php">ASSISTANCE</a>
    <a href="about.php">ABOUT US</a>
  </nav>

  <!-- Content -->
  <main class="content">
    <!-- Hero -->
    <section class="cs-hero">
      <span class="cs-badge">🚧 Coming Soon</span>
      <h1>Stop scrolling. Start finding.</h1>
      <p>
        Guided selling powered by your existing product data — zero extra maintenance.
        PinterPal maakt van elke webshop een behulpzame shop assistant.
      </p>
    </section>

    <!-- Countdown -->
    <section class="cs-countdown" aria-labelledby="countdown-title">
      <h2 id="countdown-title" class="sr-only">Countdown tot lancering</h2>
      <div class="cd-grid" id="countdown" aria-live="polite">
        <div class="cd-cell"><div class="cd-num" id="cd-days">20</div><div class="cd-label">Dagen</div></div>
        <div class="cd-cell"><div class="cd-num" id="cd-hours">10</div><div class="cd-label">Uren</div></div>
        <div class="cd-cell"><div class="cd-num" id="cd-mins">05</div><div class="cd-label">Minuten</div></div>
        <div class="cd-cell"><div class="cd-num" id="cd-secs">59</div><div class="cd-label">Seconden</div></div>
      </div>
      <noscript><p style="text-align:center;margin:.5rem 0 0;">Lancering binnenkort. Schakel JavaScript in voor de live countdown.</p></noscript>
    </section>

    <!-- Actions -->
    <section class="cs-actions" aria-label="Acties">
      <form class="notify" method="post" action="subscribe.php">
        <label for="email" class="sr-only">E-mailadres voor notificatie</label>
        <input type="email" name="email" id="email" placeholder="Jouw e-mailadres" required inputmode="email" autocomplete="email" />
        <button class="btn" type="submit">🔔 Breng me op de hoogte</button>
      </form>

      <a class="btn secondary" href="iframe.php">🎯 Probeer de demo</a>
    </section>

    <div class="cs-chips" aria-hidden="true">
      <span class="chip">GDPR-first</span>
      <span class="chip">Geen PII</span>
      <span class="chip">EU hosting</span>
      <span class="chip">Plug &amp; Play</span>
      <span class="chip">Shopify · WooCommerce · Custom</span>
    </div>

    <p class="cs-footer">
      Vragen of meedoen aan de pilot? <a href="mailto:info@pinterpal.com">info@pinterpal.com</a>
    </p>
  </main>

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
    // ====== Active link highlight op basis van padnaam ======
    (function(){
      const currentPage = (window.location.pathname.split('/').pop() || 'index.php').toLowerCase();
      document.querySelectorAll('.navbar a').forEach(a => {
        a.classList.toggle('active', a.getAttribute('href').toLowerCase() === currentPage);
      });
    })();

    // ====== Countdown ======
    (function(){
      // ▼▼ PAS DEZE DATUM/TIJD AAN (lokale tijd of ISO met timezone) ▼▼
      const LAUNCH_DATE = new Date('2025-10-01T09:00:00+02:00').getTime();

      const $d = document.getElementById('cd-days');
      const $h = document.getElementById('cd-hours');
      const $m = document.getElementById('cd-mins');
      const $s = document.getElementById('cd-secs');
      const $grid = document.getElementById('countdown');

      function pad(n){ return String(n).padStart(2,'0'); }

      function tick(){
        const now = Date.now();
        let delta = Math.max(0, LAUNCH_DATE - now);

        const days  = Math.floor(delta / (1000*60*60*24));
        delta      -= days  * (1000*60*60*24);
        const hours = Math.floor(delta / (1000*60*60));
        delta      -= hours * (1000*60*60);
        const mins  = Math.floor(delta / (1000*60));
        delta      -= mins  * (1000*60);
        const secs  = Math.floor(delta / 1000);

        $d.textContent = days;
        $h.textContent = pad(hours);
        $m.textContent = pad(mins);
        $s.textContent = pad(secs);

        if (LAUNCH_DATE - now <= 0){
          // Als live: toon “We’re live” en vervang notify-knop door Try-Me
          $grid.innerHTML = '<div class="cd-cell" style="grid-column:1/-1"><div class="cd-num">We\\'re live 🚀</div><div class="cd-label">Je kunt PinterPal nu proberen</div></div>';
          clearInterval(tmr);
        }
      }
      tick();
      const tmr = setInterval(tick, 1000);
    })();
  </script>
</body>
</html>
