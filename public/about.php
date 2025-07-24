<?php
// Start de sessie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <p class="subtitle">Keep on Developing.</p>
        
        <!-- Dynamische login-/signup of uitlogknoppen -->
        <div class="login-signup">
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="iframe.php">DEMO</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="pricing.php">PRICING</a>
        <a href="about.php" class="active">ABOUT US</a>
        <a href="pinterpalbot.php">PINTERPAL BOT</a>
    </nav>
    
    <div class="content">
        <div class="intro">
        <h2>About Us</h2>
<br>
<div class="story">
    <h3>Our Journey</h3>
    <p>
        Founded in February 2024 in The Hague, Netherlands, our journey to build this bot took ten months. From the start, we knew that working with AI would be challenging due to its relatively new and complex nature. However, we were inspired by the incredible potential AI offers to improve people's lives. This shared vision brought Hidde and Neo together. Although we attended the same high school, we had never spoken until we joined forces for this project. Our cooperation operates under the name <strong>DTI</strong>, which stands for <strong>Davids, Troenopawiro Intelligence</strong>.
    </p>
    
    <p>
        The past ten months have gone by quickly and have taught us a great deal about AI. Several times a week, we worked on the bot in the evenings after school or work. Today, we have created a bot designed to support people in their online shopping journeys. The bot provides you with quick insights into webshops and their assortments and, most importantly, saves customers time by guiding them effectively to the products that best match their needs and preferences.
    </p>

    <br>
    <h3>Our Goals</h3>
    <p>At <strong>PinterPal</strong>, we are dedicated to these 5 goals:</p>
    <ol>
        <li>Guiding shoppers to find products that match their personal wants, needs, and preferences.</li>
        <li>Creating a seamless and personalized shopping experience using our intelligent, AI-powered widget.</li>
        <li>Assisting online shoppers across all categories where the latest AI capabilities can make a meaningful difference.</li>
        <li>Constantly improving and adapting our platform to serve both customers and retailers, making online shopping more efficient, enjoyable, and time-saving.</li>
        <li>Preparing online shopping for the next era.</li>
    </ol>
</div>

        </div>

        <div class="image-row">
            <div class="image-container">
                <img src="img/team-photo2.jpg" alt="Process 1" class="process1-image">
            </div>
            <div class="image-container">
                <img src="img/team-photo1.jpg" alt="Process 2" class="process2-image">
            </div>
            <div class="image-container">
                <img src="img/nl-vlag.gif" alt="Dutch Flag" class="flag-image">
            </div>
        </div>
    </div>

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
