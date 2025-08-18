<?php
// Start sessie (voor toegang tot $_SESSION['user_logged_in'])
session_start();
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

<!-- Header -->
<header class="header">
  <div class="logo-wrap">
    <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    <a href="index.php" class="logo-boven">
      <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
    </a>
  </div>

     <!-- MIDDEN: Start Now -->
    <div class="header-cta">
      <button class="start-trial-btn" onclick="window.location.href='/company-registration.php'">
        Start Now
      </button>
    </div>

  <!-- Dynamische login-/signup of uitlog-knoppen -->
  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>


    
<!-- Navigatiebalk -->
<nav class="navbar" aria-label="Hoofdnavigatie">
  <a href="index.php" class="active">HOME</a>
  <a href="pinterpalbot.php">PINTERPAL BOT</a>
  <a href="iframe.php">DEMO</a>
  <a href="pricing.php">PRICING</a>
  <a href="assistance.php">ASSISTANCE</a>
  <a href="about.php">ABOUT US</a>
</nav>
    
   <div class="content">
  <section class="intro4">
    <div class="intro-flex">

    <h2>Help visitors find what they want in minutes. PinterPal asks the right questions and surfaces the right products—powered by your catalog</h2>
    </div>
<!-- Container die video + knop onder elkaar zet -->
<div style="display: flex; flex-direction: column; align-items: center; width: 100%;">

<!-- Promo video -->
<div class="promo-video-container" style="width: 100%; display: flex; justify-content: center;">
  <video
    id="promo-video"
    muted
    loop
    playsinline
    preload="metadata"
    class="promo-video"
    style="width: 100%; max-width: 1000px; height: auto; cursor: pointer;"
  >
    <source src="videos/demo-pinterpal-1.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>
</div>

<!-- Play/Pause/Sound buttons -->
<div style="margin-top: 10px; text-align: center;">
  <button class="video-btn" id="play-pause-toggle">▶️</button>
  <button class="video-btn" id="sound-toggle">🔇</button>
</div>

<script>
  const video = document.getElementById('promo-video');
  const playPauseBtn = document.getElementById('play-pause-toggle');
  const soundBtn = document.getElementById('sound-toggle');

  // Zorg dat video start gepauzeerd en geluid uit
  video.pause();
  video.muted = true;

  // Play/pause via knop
  function togglePlayPause() {
    if (video.paused) {
      video.play();
      playPauseBtn.textContent = '⏸️';
    } else {
      video.pause();
      playPauseBtn.textContent = '▶️';
    }
  }

  // Mute/unmute via knop
  function toggleMute() {
    video.muted = !video.muted;
    soundBtn.textContent = video.muted ? '🔇' : '🔊';
  }

  // Event listeners
  playPauseBtn.addEventListener('click', togglePlayPause);
  soundBtn.addEventListener('click', toggleMute);

  // Optioneel: ook klikken op de video zelf start/pauzeert ‘m
  video.addEventListener('click', togglePlayPause);
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


    <small class="onepager-meta">Updated: Aug 2025 • 1 page • PDF</small>
  </div>

</div>



        </div>
       
    </div>

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

</body>
</html>
