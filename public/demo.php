<?php
// ---------------------------------------------
// demo.php — Calendly embed + popup fallback
// ---------------------------------------------

// (Optioneel) simpele i18n via ?lang=nl of ?lang=en
$lang = strtolower($_GET['lang'] ?? 'nl');

$strings = [
  'nl' => [
    'title' => 'Plan een demo',
    'lead'  => 'Kies een moment dat voor jou werkt. Je afspraak komt automatisch in mijn agenda.',
    'no_planner' => 'Zie je geen planner?',
    'open_planner' => 'Open de planner in een venster'
  ],
  'en' => [
    'title' => 'Book a demo',
    'lead'  => 'Pick a time that works for you. The meeting will be added to my calendar automatically.',
    'no_planner' => "Can't see the scheduler?",
    'open_planner' => 'Open the scheduler in a popup'
  ]
];
$t = $strings[$lang] ?? $strings['nl'];

// Haal de Calendly URL uit een env var (aanbevolen) of gebruik fallback.
// Voorbeeld env: CALENDLY_URL="https://calendly.com/YOUR_HANDLE/demo?hide_gdpr_banner=1"
$calUrl = getenv('CALENDLY_URL') ?: 'https://calendly.com/YOUR_HANDLE/demo?hide_gdpr_banner=1';
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES) ?>">
<head>
  <meta charset="utf-8" />
  <title><?= htmlspecialchars($t['title'], ENT_QUOTES) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin:0; color:#0f172a; background:#f8fafc; }
    header { padding:24px; background:#fff; box-shadow:0 1px 8px rgba(0,0,0,.06); }
    .container { max-width: 1100px; margin: 0 auto; padding: 24px; }
    h1 { margin: 0 0 8px; font-size: clamp(24px, 3vw, 36px); }
    p.lead { margin: 0 0 16px; color:#475569; }
    .card { background:#fff; border-radius:16px; padding:16px; box-shadow:0 8px 30px rgba(2,6,23,.08); }
    .notice { font-size:14px; color:#64748b; margin-top:12px; }
    a { color:#4f46e5; text-decoration:none; }
    a:hover { text-decoration:underline; }
  </style>

  <!-- Calendly embed -->
  <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
  <script src="https://assets.calendly.com/assets/external/widget.js" async></script>
</head>
<body>
  <header>
    <div class="container">
      <h1><?= htmlspecialchars($t['title'], ENT_QUOTES) ?></h1>
      <p class="lead"><?= htmlspecialchars($t['lead'], ENT_QUOTES) ?></p>
    </div>
  </header>

  <main class="container">
    <div class="card">
      <!-- Inline embed -->
      <div class="calendly-inline-widget"
           data-url="<?= htmlspecialchars($calUrl, ENT_QUOTES) ?>"
           style="min-width:320px;height:900px;"></div>

      <p class="notice">
        <?= htmlspecialchars($t['no_planner'], ENT_QUOTES) ?>
        <a href="#" id="openPopup"><?= htmlspecialchars($t['open_planner'], ENT_QUOTES) ?></a>.
      </p>
    </div>
  </main>

  <script>
    // Popup fallback
    document.getElementById('openPopup')?.addEventListener('click', function(e) {
      e.preventDefault();
      if (window.Calendly) {
        Calendly.initPopupWidget({ url: "<?= htmlspecialchars($calUrl, ENT_QUOTES) ?>" });
      } else {
        window.location.href = "<?= htmlspecialchars($calUrl, ENT_QUOTES) ?>";
      }
    });
  </script>
</body>
</html>
