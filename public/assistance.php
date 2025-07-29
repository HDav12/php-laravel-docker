<?php
// Start de sessie voor toegang tot gebruikersinformatie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <p class="subtitle">At your service.</p>

        <!-- Dynamische login-/signup of uitlog-knoppen -->
        <div class="login-signup">
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="iframe.php">DEMO</a>
        <a href="assistance.php" class="active">ASSISTANCE</a>
        <a href="pricing.php">PRICING</a>
        <a href="about.php">ABOUT US</a>
        <a href="pinterpalbot.php">PINTERPAL BOT</a>
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
                <br>
                <button class="no-glow-btn" onclick="window.location.href='contact.php'">Contact Us</button>
            </div>
            <div class="option">
                <h3>Frequently Asked Questions</h3>
                <p>Find quick answers to the most common queries about PinterPal.</p>
                <br>
                <button class="no-glow-btn" onclick="window.location.href='faqs.php'">View FAQs</button>
            </div>
            <div class="option">
                <h3>User Guide</h3>
                <p>Learn how to integrate and maximize PinterPal with our step-by-step guide.</p>
                <br>
                <button class="no-glow-btn" onclick="window.location.href='guide.php'">View Guide</button>
            </div>
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