<?php
// Start de sessie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <p class="subtitle">Prepare your webshop for the new era.</p>
        <div class="login-signup">
            <!-- Dynamische navbar importeren -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <!-- Navigatiebalk -->
    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="iframe.php" class="active">DEMO</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="pricing.php">PRICING</a>
        <a href="about.php">ABOUT US</a>
        <a href="pinterpalbot.php">PINTERPAL BOT</a>
    </nav>
    
    <!-- Content -->
    <main class="content">
        <!-- Pricing Intro Section -->
        <section class="intro">
            <h2>Choose the Right Plan for You</h2>
            <p>We offer flexible plans designed to cater to different needs. Whether you're a small, concentrated business or an enterprise, PinterPal has the perfect plan for you. Browse our options and select the one that fits your needs.</p>
        </section>

        <!-- Pricing Options Section -->
        <section class="pricing-options">
            <section class="assistance">
                <div class="option">
                    <h3>Premium</h3>
                    <p>More capabilities. eg. webshop with more than 50 webshop visitors per day.</p>
                    <p>€xx,xx per month</p>
                    <br>
                    <button class="start-trial-btn">Start Trial</button>
                </div>

                <div class="option">
                    <h3>Plus</h3>
                    <p>Take your webshop to the next level.</p>
                    <p>€29.90 per month</p>
                    <br>
                    <button class="start-trial-btn" onclick="window.location.href='company-registration.php'">Start Trial</button>
                </div>
              
                <div class="option">
                    <h3>Become Allie</h3>
                    <p>Discuss advanced possibilities. Tailored advice.</p>
                    <br>
                    <button class="start-trial-btn">Plan Appointment</button>
                </div>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="contact-info">
        <p>
            <strong>KVK:</strong> 96433647<br>
            <strong>Address:</strong> Den Haag<br>
            <strong>Telephone:</strong> Your Phone Number<br>
            <strong>Email:</strong> info@pinterpal.com
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
</body>
</html>
