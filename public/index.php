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
<div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>
        <div class="login-signup">
            <!-- Dynamische login/signup of uitlog-knoppen -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>


    <!-- Navigatiebalk -->
    <nav class="navbar">
        <!-- Pas index.html -> index.php aan -->
        <a href="index.php">HOME</a>
        <a href="iframe.php" class="active">DEMO</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="pricing.php">PRICING</a>
        <a href="about.php">ABOUT US</a>
        <a href="pinterpalbot.php">PINTERPAL BOT</a>
    </nav>
    
   <div class="content">
  <section class="intro4">
    <div class="intro-flex">
      

    <h2>PinterPal guides your website visitors to their perfect match ⬇️</h2>
    </div>
<!-- Container die video + knop onder elkaar zet -->
<div style="display: flex; flex-direction: column; align-items: center; width: 100%;">

<!-- Promo video -->
<div class="promo-video-container" style="width: 100%; display: flex; justify-content: center;">
  <video id="promo-video" autoplay muted loop playsinline class="promo-video" style="width: 100%; max-width: 1000px; height: auto;">
    <source src="videos/demo-pinterpal.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>
</div>

<!-- Play/Pause/Sound buttons -->
<div style="margin-top: 10px;">
  <button class="video-btn" id="play-pause-toggle" onclick="togglePlayPause()">⏸️</button>
  <button class="video-btn" id="sound-toggle" onclick="toggleMute()">🔊</button>
</div>

<script>
  const video = document.getElementById('promo-video');
  const playPauseBtn = document.getElementById('play-pause-toggle');
  const soundBtn = document.getElementById('sound-toggle');

  function togglePlayPause() {
    if (video.paused) {
      video.play();
      playPauseBtn.textContent = '⏸️';
    } else {
      video.pause();
      playPauseBtn.textContent = '▶️';
    }
  }

  function toggleMute() {
    video.muted = !video.muted;
    soundBtn.textContent = video.muted ? '🔇' : '🔊';
  }
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




        <div class="feedback-news-container">
    <!-- Feedback sectie -->
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


        
            <!-- News sectie -->
<a href="newspage.php" style="text-decoration: none; color: inherit;">
    <div class="news" style="cursor: pointer;">
        <h3>News</h3>
        <img class="news-gif" 
             src="https://digiday.com/wp-content/uploads/sites/3/2024/02/robot-newspaper-digiday.gif"
             alt="News GIF">
    </div>
</a>


        </div>
       
    </div>

    <footer class="contact-info">
    <p>
            <strong>KVK:</strong> 96433647<br>
            <strong>Address:</strong> Den Haag<br>
            <strong>Telephone:</strong> Your Phone Number<br>
            <strong>Email:</strong> info@pinterpal.com
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
