<?php
// Start sessie (voor toegang tot $_SESSION['user_logged_in'])
require __DIR__.'/bootstrap.php';;
// Feedback versturen per e-mail
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    // Sanitiseer de invoer
    $feedback = strip_tags(trim($_POST['feedback']));

    // E-mailgegevens
    $to      = 'info@pinterpal.com';
    $subject = 'Nieuwe feedback van de website';
    $message = "Er is nieuwe feedback binnengekomen:\n\n" . $feedback;
    $headers = [];
    $headers[] = 'From: no-reply@pinterpal.com';
    $headers[] = 'Reply-To: no-reply@pinterpal.com';
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    // Mail verzenden
    if (mail($to, $subject, $message, implode("\r\n", $headers))) {
        $successMessage = 'Bedankt voor je feedback!';
    } else {
        $errorMessage = 'Er ging iets mis bij het verzenden. Probeer het later nog eens.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PinterPal</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

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


  <!-- Hamburger voor mobiel -->
  <button class="nav-toggle" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
    <span class="nav-toggle__bar"></span>
  </button>
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
    
   <div class="content">
  <section class="intro4">
    <div class="intro-flex">

  <h2>Boost sales with PinterPal —guide shoppers through your assortment in seconds ↓</h2>
<br>
<!-- Video + overlay controls -->
<div class="promo-video-wrap" style="position:relative; width:100%; max-width:700px; margin:0 auto;">
  <video
    id="promoVideo"
    playsinline
    preload="metadata"
    muted
    loop
    style="width:100%; height:auto; display:block; cursor:pointer;"
  >
    <source src="videos/ppdemo-4.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>

  <!-- Center play/pause -->
  <button type="button" id="videoToggleBtn"
    aria-label="Play"
    style="
      position:absolute; inset:0; margin:auto; width:72px; height:72px;
      border:0; border-radius:50%; display:grid; place-items:center;
      background:rgba(0,0,0,.55); cursor:pointer; transition:opacity .18s, transform .18s;
    ">
    <svg viewBox="0 0 100 100" width="38" height="38" aria-hidden="true">
      <polygon points="35,25 75,50 35,75" fill="white"></polygon>
    </svg>
  </button>

  <!-- Mute toggle (top-right) -->
  <button type="button" id="videoMuteBtn" aria-label="Mute/Unmute"
    style="
      position:absolute; top:10px; right:10px; border:0; border-radius:999px;
      padding:8px 10px; background:rgba(0,0,0,.55); color:#fff; cursor:pointer;
    ">🔇</button>
</div>

<script>
  (function () {
    const wrap  = document.querySelector('.promo-video-wrap');
    const video = document.getElementById('promoVideo');
    const btn   = document.getElementById('videoToggleBtn');
    const mute  = document.getElementById('videoMuteBtn');

    const setIcon = (playing) => {
      btn.setAttribute('aria-label', playing ? 'Pause' : 'Play');
      btn.innerHTML = playing
        ? '<svg viewBox="0 0 100 100" width="30" height="30" aria-hidden="true"><rect x="28" y="22" width="16" height="56" fill="white"></rect><rect x="56" y="22" width="16" height="56" fill="white"></rect></svg>'
        : '<svg viewBox="0 0 100 100" width="38" height="38" aria-hidden="true"><polygon points="35,25 75,50 35,75" fill="white"></polygon></svg>';
    };

    const updateOverlayVisibility = () => {
      // verberg knop tijdens afspelen; toon bij pauze of hover
      if (!video.paused) {
        btn.style.opacity = '0';
        btn.style.pointerEvents = 'none';
      } else {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
      }
    };

    const togglePlay = () => (video.paused ? video.play() : video.pause());
    const toggleMute = () => {
      video.muted = !video.muted;
      mute.textContent = video.muted ? '🔇' : '🔊';
    };

    // Events
    btn.addEventListener('click', (e) => { e.stopPropagation(); togglePlay(); });
    video.addEventListener('click', togglePlay);
    mute.addEventListener('click', (e) => { e.stopPropagation(); toggleMute(); });

    video.addEventListener('play',  () => { setIcon(true);  updateOverlayVisibility(); });
    video.addEventListener('pause', () => { setIcon(false); updateOverlayVisibility(); });
    video.addEventListener('ended', () => { setIcon(false); updateOverlayVisibility(); });

    // Hover: knop even laten zien
    wrap.addEventListener('mouseenter', () => { if (!video.paused) { btn.style.opacity = '1'; btn.style.pointerEvents = 'auto'; }});
    wrap.addEventListener('mouseleave', updateOverlayVisibility);

    // Init
    video.pause();        // start gepauzeerd
    video.muted = true;   // start muted
    setIcon(false);
    updateOverlayVisibility();
  })();
</script>



  </div>


  <!-- Start Now knop in geel vlak -->
<div class="intro-text" style="padding: 40px; text-align: center; width: 100%;">
    <button class="start-trial-btn" onclick="window.location.href='/company-registration.php'"
      style="font-size: 18px; padding: 12px 24px; margin-top: 20px;">
      Start Now
    </button>
  </div>



</div>



</div>




 <div class="feedback-news-container feedback-news-onepager-container">

  <!-- Feedback -->
  <div class="feedback">
    <h3>Share your feedback / thoughts with us</h3>
    <?php if ($successMessage): ?>
      <p class="success-message"><?= $successMessage ?></p>
    <?php elseif ($errorMessage): ?>
      <p class="error-message"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form class="feedback-form" action="" method="POST">
      <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>
      <button type="submit">Submit Feedback</button>
    </form>
  </div>

  <!-- News (anchor zelf is de tile, zodat flex-styling werkt) -->
  <a href="newspage.php" class="news" style="text-decoration:none; color:inherit; cursor:pointer;" rel="noopener">
    <h3>News</h3>
    <img class="news-gif"
         src="https://digiday.com/wp-content/uploads/sites/3/2024/02/robot-newspaper-digiday.gif"
         alt="News GIF">
  </a>

  <!-- One-pager -->
  <div class="onepager">
    <h3>PinterPal One-Pager</h3>
    <p>Quick overview of PinterPal’s value, features, and pricing.</p>

    <!-- Optionele preview -->
    <!-- <img src="/assets/onepager-preview.png" alt="PinterPal One-Pager preview" class="onepager-preview"> -->

  <div class="onepager-actions">
  <a class="btn-onepager"
     href="/assets/PinterPal-one-pager.docx"
     download="PinterPal-one-pager.docx"
     type="application/vnd.openxmlformats-officedocument.wordprocessingml.document">
    Download One-Pager (DOCX)
  </a>
  </a>
</div>


    <small class="onepager-meta">Updated: Aug 2025 • 1 page • DOCX</small>
  </div>

</div>



        </div>
       
    </div>

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

    <!-- Eventueel aanpassen van de 'active' class logica (index.html -> index.php) -->
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

                // Als we bijvoorbeeld nu index.php in plaats van index.html hebben
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
