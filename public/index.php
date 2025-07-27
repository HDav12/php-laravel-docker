<?php
// Start sessie (voor toegang tot $_SESSION['user_logged_in'])
session_start();
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
            <h1>PINTERPAL.</h1>
        </a>
        <div class="login-signup">
            <!-- Dynamische login/signup of uitlog-knoppen -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
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
  <div class="intro">
    <div class="intro-flex">
      
      <!-- Text block -->
      <div class="intro-text">
        <h2>PinterPal: Your AI‑powered assistant that guides online shoppers to the right product — fast, personal, and effective.</h2>
        <hr>
<br>
        <p>
          <strong style="font-size:24px;">The Problem:</strong><br><br>
          Online store owners struggle to convert visitors into paying customers. Many shoppers get lost or overwhelmed by too many options, causing them to leave without buying.
        </p>
<br><br>

        <p>
          <strong style="font-size:24px;">The Solution:</strong><br><br>
          PinterPal is your AI‑powered assistant, designed to guide each visitor through their shopping journey in under a minute:
        </p>

        <ul>
          <li><strong>Delivers personalized experiences</strong> with smart product recommendations.</li>
          <li><strong>Boosts conversion rates</strong> by helping customers find what they truly need — fast.</li>
          <li><strong>Saves time</strong> by automating product selection through intelligent Q&A.</li>
        </ul>
<br><br><br>
        <p>
          PinterPal is fully integrated into your webshop, acting like a digital shop assistant that turns unsure browsers into confident buyers. It’s fast, friendly, and built to enhance the customer experience.
        </p>

        <p><strong>Step into the future of eCommerce with PinterPal.</strong></p>
        <p>Hit the button and take your webshop to the new Era.</p>
<br><br>
        <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">
          Start Now
        </button>
      </div>

      <!-- Yellow logo on the right -->
      <div class="intro-image">
        <img src="img/foto-index.png" alt="Index-foto" class="index-logo">
      </div>

    </div>
  </div>
</div>


        <div class="feedback-news-container">
    <!-- Feedback sectie -->
    <div class="feedback">
        <h3>Share your feedback / thoughts with us</h3>
        <p>
            We are committed to continuously enhancing PinterPal and greatly value your input. 
            Thank you in advance for sharing your valuable feedback!
           
        </p>
         <br>
        <?php if (isset($successMessage)) : ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
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
</body>
</html>
