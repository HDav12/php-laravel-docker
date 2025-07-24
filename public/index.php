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
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <p class="subtitle">Take your webshop to the new Era</p>
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
            <h2>"PinterPal: Your go-to assistant for understanding online shoppers' wants and needs, offering expert assistance across almost all categories</h2>
            <p>___________________________________________________________________________________________________________________________________________________________</p>
            <p>
            <strong style="font-size: 24px;">The Problem:</strong><br><br>
                Webshop owners face the challenge of converting visitors into customers. Many shoppers get overwhelmed by too many choices...
            </p>
            <br>
            <p>
            <strong style="font-size: 24px;">The Solution:</strong><br><br>
            PinterPal is your smart assistant, designed to guide customers effortlessly through their shopping journey:
            </p>
            <ul><br>
                <li><strong>Personalize the experience</strong> with tailored product recommendations.</li>
                <li><strong>Boost conversions</strong> by making it easier for customers to find what they need.</li>
                <li><strong>Save time</strong> by automating product suggestions based on customer input.</li>
            </ul>
            <br>
            <p>Seamlessly integrated into your webshop, PinterPal turns indecisive visitors into loyal buyers...</p>
            <p><strong>Take your webshop to the new Era.</strong></p>
            <br>
            <p>Push the button and upgrade your website! The first month is on the house!</p>
            <p>___________________________________________________________________________________________________________________________________________________________</p>

            <!-- Start-knop -->
            <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">
                Start Now
            </button>
        </div>

        <div class="feedback-news-container">
    <!-- Feedback sectie -->
    <div class="feedback">
        <h3>Share your feedback / thoughts with us</h3>
        <p>
            We are committed to continuously enhancing PinterPal and greatly value your input. 
            Thank you in advance for sharing your valuable feedback!
        </p>
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
