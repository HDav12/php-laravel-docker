<?php
// public/terms-conditions.php

// Dynamische variabelen – pas aan waar nodig
$lastUpdated   = '2025-08-02';
$address       = 'Elandstraat 115, Den Haag';
$kvkNumber     = '96433647';
$companyPlace  = 'Den Haag';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Algemene Voorwaarden – PinterPal</title>
  <link rel="stylesheet" href="/css/main.css">
</head>
<body class="terms">

  <!-- Header + Navigatie -->
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
    <h1>📄 Algemene Voorwaarden</h1>
    <p><strong>PinterPal</strong> / (Davids Troenopawiro Intelligence)</p>
    <p class="last-updated"><em>Laatste update: <?php echo date('j F Y', strtotime($lastUpdated)); ?></em></p>

    <section>
      <h2>1. Definities</h2>
      <ul>
        <li><strong>Dienst:</strong> de AI-widget van PinterPal die gebruikers begeleidt bij het kiezen uit een assortiment.</li>
        <li><strong>Klant:</strong> iedere natuurlijke of rechtspersoon die een overeenkomst sluit met PinterPal.</li>
        <li><strong>PinterPal:</strong> handelsnaam van Davids Troenopawiro Intelligence, gevestigd te <?php echo $address; ?>, KVK-nummer <?php echo $kvkNumber; ?>.</li>
      </ul>
    </section>
    <section>
      <h2>2. Toepasselijkheid</h2>
      <p>Deze voorwaarden gelden voor alle overeenkomsten tussen PinterPal en de Klant, tenzij schriftelijk anders is overeengekomen.</p>
    </section>
    <section>
      <h2>3. Gebruik van de Dienst</h2>
      <ul>
        <li>De Klant verkrijgt een niet-exclusief, niet-overdraagbaar gebruiksrecht op de widget, uitsluitend voor de eigen website(s) of applicatie(s).</li>
        <li>Het is niet toegestaan de Dienst te herverkopen, sublicenseren, reverse-engineeren of kopiëren.</li>
        <li>Gebruik door derden (zoals agencies of resellers) is alleen toegestaan met schriftelijke toestemming van PinterPal.</li>
      </ul>
    </section>
    <section>
      <h2>4. Eigendom en Intellectueel Eigendom</h2>
      <p>Alle rechten op de technologie, broncode, AI-modellen, scripts en visuele componenten blijven eigendom van PinterPal. Klanten mogen geen onderdelen van de Dienst kopiëren, bewerken of hergebruiken buiten het overeengekomen gebruiksdoel.</p>
    </section>
    <section>
      <h2>5. Betaling en Abonnement</h2>
      <ul>
        <li>De Dienst wordt geleverd op basis van een maandelijks abonnement.</li>
        <li>Abonnementen worden automatisch per maand verlengd en zijn per maand opzegbaar met 1 maand opzegtermijn via e-mail of het dashboard.</li>
        <li>Betaling dient te gebeuren binnen 14 dagen na factuurdatum.</li>
        <li>Bij niet tijdige betaling heeft PinterPal het recht de Dienst tijdelijk op te schorten.</li>
      </ul>
    </section>
    <section>
      <h2>6. Beta- en testgebruik</h2>
      <p>De Dienst is momenteel in ontwikkeling en kan als beta worden aangeboden. Bij gebruik van de beta-dienst gelden geen garanties of aansprakelijkheid. Gebruik is volledig op eigen risico.</p>
    </section>
    <section>
      <h2>7. Aansprakelijkheid</h2>
      <p>PinterPal is niet aansprakelijk voor directe of indirecte schade, verlies van omzet of gegevens, tenzij er sprake is van opzet of grove nalatigheid. De maximale aansprakelijkheid is beperkt tot het bedrag dat in de laatste 12 maanden door de Klant is betaald.</p>
    </section>
    <section>
      <h2>8. Privacy en Data</h2>
      <ul>
        <li>PinterPal verwerkt persoonsgegevens volgens de AVG (GDPR).</li>
        <li>Klanten zijn zelf verantwoordelijk voor het plaatsen van een privacyverklaring en het informeren van hun gebruikers over cookies en tracking.</li>
        <li>Klantdata blijven eigendom van de Klant, maar PinterPal mag geanonimiseerde gebruiksdata gebruiken om de Dienst te verbeteren.</li>
      </ul>
    </section>
    <section>
      <h2>9. Onderhoud en Beschikbaarheid</h2>
      <ul>
        <li>PinterPal streeft naar 99,5% uptime.</li>
        <li>Gepland onderhoud wordt minimaal 24 uur van tevoren aangekondigd.</li>
        <li>De widget kan tijdelijk offline zijn voor updates, bugfixes of technische aanpassingen.</li>
      </ul>
    </section>
    <section>
      <h2>10. Beëindiging en Opschorting</h2>
      <ul>
        <li>Beide partijen kunnen de overeenkomst maandelijks opzeggen met een maand opzegtermijn.</li>
        <li>Bij misbruik of overtreding van deze voorwaarden mag PinterPal de toegang tot de Dienst beperken of blokkeren.</li>
      </ul>
    </section>
    <section>
      <h2>11. Toepasselijk recht en geschillen</h2>
      <p>Op deze voorwaarden is het Nederlands recht van toepassing. Geschillen worden bij voorkeur eerst in overleg opgelost. Lukt dit niet, dan worden ze voorgelegd aan de bevoegde rechter in <?php echo $companyPlace; ?>.</p>
    </section>
  </main>

  <!-- Optioneel: include je site-footer -->
  <?php // include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
