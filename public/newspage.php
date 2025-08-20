<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .news-page-container {
            max-width: 800px;
            margin: 5vh auto;
            padding: 2rem;
            background-color: #ffc107;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .news-page-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #077082;
        }

        .news-item {
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #077082 !important;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .news-item h3 {
            margin-bottom: 0.5rem;
        }

        .news-item p {
            margin-bottom: 0.5rem;
        }

        .news-item a {
            color: #077082;
            text-decoration: none;
            font-weight: bold;
        }

        .news-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">
        <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
             <h1 class="logo-boven">
            <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
            </h1>

        </a>
        
    </div>
  <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="newspage.php" class="active">NEWS</a>
        <a href="pinterpalbot.php">PINTERPAL BOT</a>
        <a href="iframe.php">TRY ME</a>
        <a href="pricing.php">PRICING</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="about.php">ABOUT US</a>
    </nav>
    <div class="news-page-container">
        <h2>Latest News</h2>

  



        <div class="news-item">
            <h3>Feature Updates in February</h3>
            <p>New features added to our platform in February. Learn how these updates improve your interactions with our widget.</p>
            <img src="img/pinterpal-roadmap.jpg" alt="Value Towards Customers" style="width: 100%; border-radius: 10px;">        </div>

        <!-- Add more news articles here -->

        <!-- Values Towards Our Retailers -->
<div class="news-item" style="background-color: #f9f9f9; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
    <a href="article2.php" style="text-decoration: none; color: inherit;">
        <h3 style="text-align: center;">Values Towards Our Retailers</h3>
        <p style="text-align: center;">We’ve partnered with retailers to bring you a more personalized and seamless shopping experience.</p>
        <img src="img/retailers.png" alt="Values Towards Retailers" style="width: 100%; border-radius: 10px;">
    </a>
</div>

<!-- Value Towards Customers -->
<div class="news-item" style="background-color: #f9f9f9; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
    <a href="article1.php" style="text-decoration: none; color: inherit;">
        <h3 style="text-align: center;">Value Towards Customers</h3>
        <p style="text-align: center;">Our latest AI-powered shopping assistant is now live! Discover how it enhances your shopping experience.</p>
        <img src="img/customer-value.png" alt="Value Towards Customers" style="width: 100%; border-radius: 10px;">
    </a>
</div>

    </div>
</body>
</html>
