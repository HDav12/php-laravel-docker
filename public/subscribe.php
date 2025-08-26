<?php
// subscribe-1.php
// Verwerkt aanmeldingen voor "Coming Soon" en mailt info@pinterpal.com

declare(strict_types=1);
session_start();

// ── Config ─────────────────────────────────────────────────────────────────────
$SITE_NAME   = 'PinterPal';
$TO_EMAIL    = 'info@pinterpal.com';                 // ontvanger
$FROM_EMAIL  = 'no-reply@pinterpal.com';             // bij voorkeur eigen domein
$STORE_CSV   = __DIR__ . '/data/subscribers.csv';    // opslaan van leads
$THROTTLE_S  = 60;                                   // min. seconden tussen inzendingen (per sessie)
// ───────────────────────────────────────────────────────────────────────────────

// Helper: nette redirect
function redirect_to(string $url, int $code = 303): void {
  header("Location: $url", true, $code);
  exit;
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Alleen POST
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
  if ($isAjax) {
    http_response_code(405);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
  }
  http_response_code(405);
  echo 'Method not allowed';
  exit;
}

// Honeypot (voeg in formulier een hidden field name="hp")
if (!empty($_POST['hp'] ?? '')) {
  // Doen alsof het gelukt is (bots geen feedback geven)
  if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => true, 'message' => 'Bedankt!']);
    exit;
  }
  redirect_to('coming-soon.php?ok=1');
}

// Rate limiting (eenvoudig per sessie)
$now = time();
if (isset($_SESSION['last_subscribe']) && ($now - (int)$_SESSION['last_subscribe']) < $THROTTLE_S) {
  $wait = $THROTTLE_S - ($now - (int)$_SESSION['last_subscribe']);
  $msg = "Even geduld… probeer het over {$wait}s opnieuw.";
  if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
  }
  redirect_to('coming-soon.php?error=rate');
}

// Input
$email = trim((string)($_POST['email'] ?? ''));
$name  = trim((string)($_POST['name']  ?? ''));
$ref   = trim((string)($_POST['ref']   ?? ($_SERVER['HTTP_REFERER'] ?? '')));

// Validatie
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => false, 'error' => 'Ongeldig e-mailadres.']);
    exit;
  }
  redirect_to('coming-soon.php?error=email');
}

// Normaliseer
$email_lc = strtolower($email);
$ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ua       = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$ts_iso   = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('c');

// Opslaan in CSV (optioneel maar handig)
try {
  $dir = dirname($STORE_CSV);
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }

  // Dup-check (simpel)
  $isDup = false;
  if (file_exists($STORE_CSV)) {
    $fh = fopen($STORE_CSV, 'rb');
    if ($fh) {
      while (($row = fgetcsv($fh)) !== false) {
        if (isset($row[1]) && strtolower($row[1]) === $email_lc) { $isDup = true; break; }
      }
      fclose($fh);
    }
  }

  $fh = fopen($STORE_CSV, 'ab');
  if ($fh) {
    // Kopregel toevoegen als nieuw bestand
    if (0 === filesize($STORE_CSV)) {
      fputcsv($fh, ['timestamp_utc','email','name','ip','user_agent','referer','duplicate']);
    }
    fputcsv($fh, [$ts_iso, $email_lc, $name, $ip, $ua, $ref, $isDup ? 'yes' : 'no']);
    fclose($fh);
  }
} catch (Throwable $e) {
  // Fout bij opslaan? Niet fataal; we gaan door met mailen.
}

// Mail naar info@pinterpal.com
$subject = "Nieuwe Coming Soon aanmelding: {$email_lc}";
$lines = [
  "Er is een nieuwe aanmelding voor {$SITE_NAME}.",
  "",
  "Naam: " . ($name !== '' ? $name : '(niet ingevuld)'),
  "E-mail: {$email_lc}",
  "IP: {$ip}",
  "User-Agent: {$ua}",
  "Referer: " . ($ref !== '' ? $ref : '(onbekend)'),
  "Tijd (UTC): {$ts_iso}",
];
$body = implode("\r\n", $lines);

// Headers
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = "From: {$SITE_NAME} <{$FROM_EMAIL}>";
$headers[] = "Reply-To: {$email_lc}";
$headers[] = 'X-Mailer: PHP/' . phpversion();

$mailOK = @mail($TO_EMAIL, $subject, $body, implode("\r\n", $headers));

// Throttle markeren
$_SESSION['last_subscribe'] = $now;

// Response
if ($isAjax) {
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode([
    'ok'      => (bool)$mailOK,
    'message' => $mailOK ? 'Bedankt, je staat op de lijst!' : 'Opgeslagen. Mailmelding kon niet worden verzonden, we kijken dit na.',
  ]);
  exit;
}

// Non-AJAX: eenvoudige bevestigingspagina
?><!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bedankt — PinterPal</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    :root{ --gap:1rem; --maxw:900px; }
    body{ margin:0; font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif; }
    .wrap{ max-width:var(--maxw); margin:0 auto; padding:2rem var(--gap); text-align:center; }
    .card{
      margin: 1rem auto 0; max-width:680px; padding:1rem;
      background: var(--secondary-color); border:3px solid #0a7082;
      border-radius:16px; box-shadow:0 12px 28px rgba(0,0,0,.18);
    }
    .btn{ display:inline-block; margin-top:1rem; padding:.7rem 1rem; border-radius:.75rem;
      border:2px solid #0a7082; background:#fff; color:#111; text-decoration:none; font-weight:800; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Bedankt! 🎉</h1>
    <div class="card">
      <p>We hebben je e-mailadres <strong><?= htmlspecialchars($email_lc, ENT_QUOTES, 'UTF-8') ?></strong> ontvangen.</p>
      <?php if ($mailOK): ?>
        <p>Ons team is geïnformeerd via <strong>info@pinterpal.com</strong>.</p>
      <?php else: ?>
        <p>Je inschrijving is opgeslagen. De e-mailmelding kon niet worden verzonden — we kijken dit na.</p>
      <?php endif; ?>
      <a class="btn" href="coming-soon.php">← Terug naar Coming Soon</a>
    </div>
  </div>
</body>
</html>
