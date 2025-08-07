<?php
// public/terms-conditions.php

// Dynamische variabelen – pas aan waar nodig
$lastUpdated   = '2025-08-02';
$address       = 'Elandstraat 115, Den Haag';
$kvkNumber     = '96433647';
$companyPlace  = 'Den Haag';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinterPal Bot Explained</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Achtergrond wit maken -->
    <style>
      html, body {
      }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>
        <p class="subtitle">PinterPal Bot</p>
        <div class="login-signup">
            <!-- Dynamische login/signup of uitlog-knoppen -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <!-- Navigatiebalk -->
   <nav class="navbar">
        <a href="index.php">HOME</a>
        <a href="pinterpalbot.php" class="active">PINTERPAL BOT</a>
        <a href="iframe.php">DEMO</a>
        <a href="pricing.php">PRICING</a>
        <a href="assistance.php">ASSISTANCE</a>
        <a href="about.php">ABOUT US</a>
    </nav>
    <!-- rest van je pagina… -->
</body>
</html>

<div class="content">
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
<Br></Br>
    <h1>📄 Terms and conditions</h1>
    <p><strong>PinterPal</strong> / (Davids Troenopawiro Intelligence)</p>
    <p class="last-updated"><em>Last update: <?php echo date('j F Y', strtotime($lastUpdated)); ?></em></p>

  <Br></Br>
  <section>
    <h2>1. Definitions</h2>
    <ul>
      <li><strong>Service:</strong> the PinterPal AI widget that guides users in choosing from an assortment.</li>
      <li><strong>Customer:</strong> any natural person or legal entity entering into an agreement with PinterPal.</li>
      <li><strong>PinterPal:</strong> trade name of Davids Troenopawiro Intelligence, located at <?php echo $address; ?>, Chamber of Commerce number <?php echo $kvkNumber; ?>.</li>
    </ul>
  </section>
  <section>
    <h2>2. Applicability</h2>
    <p>These terms apply to all agreements between PinterPal and the Customer, unless agreed otherwise in writing.</p>
  </section>
  <section>
    <h2>3. Use of the Service</h2>
    <ul>
      <li>The Customer obtains a non-exclusive, non-transferable right to use the widget solely for their own website(s) or application(s).</li>
      <li>Reselling, sublicensing, reverse-engineering, or copying the Service is prohibited.</li>
      <li>Use by third parties (such as agencies or resellers) is only allowed with PinterPal’s written consent.</li>
    </ul>
  </section>
  <section>
    <h2>4. Ownership and Intellectual Property</h2>
    <p>All rights to the technology, source code, AI models, scripts, and visual components remain the property of PinterPal. Customers may not copy, modify, or reuse any part of the Service beyond the agreed usage.</p>
  </section>
  <section>
    <h2>5. Payment and Subscription</h2>
    <ul>
      <li>The Service is provided on a monthly subscription basis.</li>
      <li>Subscriptions automatically renew monthly and can be canceled with one month’s notice via email or through the dashboard.</li>
      <li>Payment must be made within 14 days of the invoice date.</li>
      <li>If payment is late, PinterPal reserves the right to suspend the Service temporarily.</li>
    </ul>
  </section>
  <section>
    <h2>6. Beta and Test Use</h2>
    <p>The Service is currently under development and may be offered as a beta. No warranties or liabilities apply to beta usage. Use is entirely at your own risk.</p>
  </section>
  <section>
    <h2>7. Liability</h2>
    <p>PinterPal is not liable for direct or indirect damages, loss of revenue or data, unless due to intent or gross negligence. Maximum liability is limited to the amount paid by the Customer in the last 12 months.</p>
  </section>
  <section>
    <h2>8. Privacy and Data</h2>
    <ul>
      <li>PinterPal processes personal data in accordance with the GDPR.</li>
      <li>Customers are responsible for publishing a privacy statement and informing their users about cookies and tracking.</li>
      <li>Customer data remains the Customer’s property, but PinterPal may use anonymized usage data to improve the Service.</li>
    </ul>
  </section>
  <section>
    <h2>9. Maintenance and Availability</h2>
    <ul>
      <li>PinterPal strives for 99.5% uptime.</li>
      <li>Scheduled maintenance will be announced at least 24 hours in advance.</li>
      <li>The widget may be temporarily offline for updates, bug fixes, or technical adjustments.</li>
    </ul>
  </section>
  <section>
    <h2>10. Termination and Suspension</h2>
    <ul>
      <li>Either party may terminate the agreement monthly with one month’s notice.</li>
      <li>In case of misuse or breach of these terms, PinterPal may restrict or block access to the Service.</li>
    </ul>
  </section>
  <section>
    <h2>11. Governing Law and Dispute Resolution</h2>
    <p>These terms are governed by Dutch law. Disputes should first be resolved through consultation. If that fails, they will be submitted to the competent court in <?php echo $companyPlace; ?>.</p>
  </section>


  <!-- Optioneel: include je site-footer -->
  <?php // include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
