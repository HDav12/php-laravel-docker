<?php
// Start sessie (voor toegang tot $_SESSION['user_logged_in'])
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embedded Website - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background-color: var(--primary-color, #0a7082);
        }

        .header {
            background-color: var(--primary-color, #0a7082);
            padding: 20px;
            text-align: center;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 2.5vw;
            color: var(--secondary-color, #ffc107);
        }

        .subtitle-demo-page {
            font-size: 18px;
            color: var(--secondary-color, #ffc107);
            font-weight: bold;
        }

        .login-signup {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .login-signup img {
            max-width: 150px; /* Pas breedte aan naar jouw voorkeur */
            max-height: 80px; /* Pas hoogte aan naar jouw voorkeur */
            border-radius: 0; /* Verwijder ronde hoeken */
        }

        .navbar {
            background-color: var(--primary-color, #0a7082);
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 10px 0;
        }

        .navbar a {
            color: var(--secondary-color, #ffc107);
            text-decoration: none;
            font-size: 1.5vw;
            padding: 0.5vw 1vw;
            background-color: #6c757d;
            border: 2px solid var(--primary-color, #0a7082);
            border-radius: 10px;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: var(--secondary-color, #ffc107);
            color: var(--font-color, #000);
        }

        .navbar a.active {
            background-color: var(--secondary-color, #ffc107);
            color: var(--font-color, #000);
        }

        .iframe-container {
            height: calc(100vh - 100px); /* De iframe neemt de resterende ruimte in */
            width: 100vw; /* Volledige breedte van het scherm */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .iframe-container iframe {
            width: 95%;
            height: 100%;
            border: 2px solid var(--secondary-color, #ffc107);
            border-radius: 15px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Specifiek voor de "Demo" pagina */
        body.iframe-page .navbar a.active {
            color: var(--secondary-color, #ffc107); /* Gele tekst op de actieve tab */
        }
    </style>
</head>
<body class="iframe-page">
    <!-- Header -->
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>
        <p class="subtitle-demo-page">A DTI Product</p>
        <div class="login-signup">
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
    
    <!-- Iframe-container -->
    <div class="iframe-container">
        <iframe src="http://127.0.0.1:8001/widget" title="Embedded Website"></iframe>
    </div>
</body>
</html>
