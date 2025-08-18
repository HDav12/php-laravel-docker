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
  
<!-- Header -->
<header class="header">
  <div class="logo-wrap">
    <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    <a href="index.php" class="logo-boven">
      <img src="img/PINTERPAL-wordmark.png" alt="PINTERPAL">
    </a>
  </div>


  <!-- Dynamische login-/signup of uitlog-knoppen -->
  <div class="login-signup">
    <?php include 'navbar.php'; ?>
  </div>
</header>

    <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="pinterpalbot.php" class="active">PINTERPAL BOT</a>
        <a href="iframe.php">DEMO</a>
        <a href="pricing.php">PRICING</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="about.php">ABOUT US</a>
    </nav>
    
    <div class="content">
        <div class="intro3">
        <h2>About Us</h2>
<br>
<div class="story">

<p>
  Hidde:  The idea for PinterPal was born out of a last-minute Christmas scramble. In December 2023, I found myself running out of time to find a gift for my mom. I had a general idea of what she liked and what she might use — but no clear direction, and she hadn’t given any hints. 
    
    That’s when it hit me: why isn’t there a bot that guides you through a webshop and helps you discover the perfect gift? Not just random suggestions, but a smart, interactive assistant that narrows things down based on your needs.
    
    The more I thought about it, the more I realized — this should exist. That moment became the spark for PinterPal, and I knew I had to turn the idea into something real.
    
    But then I started thinking... why stop at gifts? What if the same kind of assistant could help you choose and arrange a vacation, recommend a good movie to watch, help you pick the perfect bottle of wine for a party, the possibilities are alsmost endless. The potential of this concept went far beyond just shopping — and that’s when the idea really started to grow.
</p>
    <br><br>

    <h3>Our Journey</h3>
    <p>
        Founded in February 2024 - august 2025 in The Hague, Netherlands, our journey to build this bot took over a year. From the start, we knew that working with AI would be challenging due to its relatively new and complex nature. However, we were inspired by the incredible potential AI offers to improve people's lives. This shared vision brought Hidde and Neo together. Although we attended the same high school, we had never spoken until we joined forces for this project. Our cooperation operates under the name <strong>DTI</strong>, which stands for <strong>Davids, Troenopawiro Intelligence</strong>.
    </p>
    
    <p>
        The past 1.5 year have gone by quickly and have taught us a great deal about AI. Several times a week, we worked on the bot in the evenings after school or work. Today, we have created a bot designed to support people in their online shopping journeys. The bot provides you with quick insights into webshops and their assortments and, most importantly, saves customers time by guiding them effectively to the products that best match their needs and preferences.
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
                <img src="img/podcast-afbeelding.png" alt="Process 2" class="process2-image">
            </div>
            <div class="image-container">
                <img src="img/nl-vlag.gif" alt="Dutch Flag" class="flag-image">
            </div>
        </div>
    </div>

  <section class="team">
  <h2>PinterPal Team</h2>
  <div class="team-grid">
    <!-- Hidde -->
    <div class="team-member">
      <img src="img/about-hidde.png" alt="Hidde Davids" class="team-photo">
      <h3>Hidde Davids</h3>
      <p class="role">Founder &amp; Director</p>
      <p class="bio">
        Hidde Davids is from The Hague and holds a degree in Commercial Economics. He began his career at an accounting‑software firm before moving into cybersecurity, where he honed his analytical skills. Outside of work, he enjoys target shooting and spending quality time with friends and family. Hidde is known for his reliability, respectfulness, and commitment. He prefers a quick phone call over email or messaging, and draws inspiration from his father and grandfather. Sitting still on vacation isn’t his style—he’s always looking for the next opportunity to learn and grow.
<Br> <Br>
Biggest fear: flying insects
<Br>
Favorite animals: dog and duck
<Br>
Biggest life lesson: you’re the maker of your own life
<Br>
Favorite foods: teppanyaki / döner / chicken chicharon
      </p>
      <!-- onderaan binnen <div class="team-member"> … -->
<a href="https://www.linkedin.com/in/hidde-davids-805149214/" target="_blank" rel="noopener noreferrer">
  <img src="img/linkedin-icon.png" alt="LinkedIn profiel" class="linkedin-icon">
</a>

    </div>
    <!-- Neo -->
    <div class="team-member">
      <img src="img/about-neo.png" alt="Neo Troenopawiro" class="team-photo">
      <h3>Neo Troenopawiro</h3>
      <p class="role">Founder &amp; Head Developer</p>
      <p class="bio">
        <Br> <Br>        <Br> <Br>

My name is Neo and I study at Rotterdam University of Applied Sciences. I’m the head programmer at PinterPal, which means I’m mainly responsible for writing and maintaining the code.
 <Br> <Br>
A few things about me:
 <Br> 
I love gaming and binge‑watching anime series.
 <Br> 
My biggest fear is thalassophobia (the fear of very large objects).
 <Br> 
My favorite animal is the cat.
 <Br> 
One of my biggest life lessons is that there are many roads to Rome—don’t give up if you hit a roadblock; just find another way to reach your goal.
 <Br> 
My favorite: foods are sushi and pasta.
    <Br> <Br>
 
    </p>
    <!-- onderaan binnen <div class="team-member"> … -->
<a href="https://www.linkedin.com/in/neo-troenopawiro-729725198/" target="_blank" rel="noopener noreferrer">
  <img src="img/linkedin-icon.png" alt="LinkedIn profiel" class="linkedin-icon">
</a>

</div>
    <!-- Neo -->
    <div class="team-member">
      <img src="img/about-ayah.png" alt="Ayah" class="team-photo">
      <h3>Ayah</h3>
      <p class="role">Assistant to the regional manager</p>
      <p class="bio">
        <Br> <Br>        <Br> <Br>

Hi, I’m Aya, I’m 10 years old and I love sleeping and getting attention. I’m originally from Hungary.
<br><br>
A few things about me:
<br>
My absolute favorite activities are sleeping and eating.
<br>
My biggest fear is going outside when it’s raining.
<br>
Favorite animal: The Pigeon. I love chasing them around.
<br>
When it comes to food, I enjoy almost everything… but cookies are the best.
<br>
My biggest life lesson? If you stare at someone long enough, you’ll always get what you want.
    <Br> <Br>
 
    </p>
    <!-- onderaan binnen <div class="team-member"> … -->
<a href="https://www.linkedin.com/groups/1897093/" target="_blank" rel="noopener noreferrer">
  <img src="img/linkedin-icon.png" alt="LinkedIn profiel" class="linkedin-icon">
</a>

    </div>
    <!-- Dion -->
    <div class="team-member">
      <img src="img/about-dion.png" alt="Dion Westeneng" class="team-photo">
      <h3>Dion Westeneng</h3>
      <p class="role">Developer & Head of Security</p>
     <p class="bio">
  Dion (26, Baarn) ships the front-end and guards our stack. ICT graduate with almost 5 years in the security domain — he makes PinterPal fast, safe, and smooth.<br><br>
  Loves: techno & house, the gym, a good party, and riding motorcycles.<br><br>
  Biggest fear: the day Pegassi stops making music.<br>
  Favorite animal: cat.<br>
  Biggest life lesson: always keep smiling.<br>
  Favorite foods: tapas.
</p>

    <!-- onderaan binnen <div class="team-member"> … -->
<a href="https://www.linkedin.com/in/dion-westeneng-7b729b16a/" target="_blank" rel="noopener noreferrer">
  <img src="img/linkedin-icon.png" alt="LinkedIn profiel" class="linkedin-icon">
</a>

    </div>
    <!-- Michaël -->
    <div class="team-member">
      <img src="img/about-michael.png" alt="Michaël Vogel" class="team-photo">
      <h3>Michaël Vogel</h3>
      <p class="role">Sales</p>
      <p class="bio">
      <Br>
      Hi, I'm Michaël, a young, sometimes overly enthusiastic guy who focuses on getting the most out of relationships.
      <Br>      <Br>

My biggest fear is best described with a scene from Coach Carter: Our deepest fear is not that we are inadequate, our deepest fear is that we are powerful beyond measure.
<Br>
My favorite animal is a dog because of its endless loyalty.
<Br>
The biggest lesson I've learned so far is that no matter how fast a lie is, the truth will always catch up.

<Br>
My favorite food has to be pizza, because you can do anything with it depending on what you're craving in the moment.

<Br>
      </p>
      <!-- onderaan binnen <div class="team-member"> … -->
<a href="https://www.linkedin.com/in/micha%C3%ABl-vogel/" target="_blank" rel="noopener noreferrer">
  <img src="img/linkedin-icon.png" alt="LinkedIn profiel" class="linkedin-icon">
</a>

    </div>
  </div>
</section>


    <script></script>

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
