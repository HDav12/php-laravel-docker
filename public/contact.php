<?php
// contact.php — styled like assistance.php: first socials/phone, then a wider form

// ---- session + logging ----
ini_set('display_errors','0');
ini_set('log_errors','1');
ini_set('error_log','php://stderr');

if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params(['secure'=>true,'httponly'=>true,'samesite'=>'Lax','path'=>'/']);
  session_name('__Host-ppsid');
  session_start();
}

function elog($m){ @file_put_contents('php://stderr', "[contact] ".str_replace(["\r","\n"],' ',(string)$m)."\n"); }
function base_url(){
  $https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
  $proto = $https ? 'https' : 'http';
  $host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
  return $proto.'://'.$host;
}
function new_token(){ return bin2hex(random_bytes(16)); }
function csrf_token(){ if (empty($_SESSION['csrf_contact'])) $_SESSION['csrf_contact'] = new_token(); return $_SESSION['csrf_contact']; }
function check_rate_limit($windowSec=600, $max=3){
  $now = time();
  $_SESSION['contact_times'] = array_values(array_filter(($_SESSION['contact_times'] ?? []), fn($t)=>($now-$t)<$windowSec));
  if (count($_SESSION['contact_times']) >= $max) return false;
  $_SESSION['contact_times'][] = $now; return true;
}

// ---- ENV config ----
$TO_EMAIL = getenv('CONTACT_TO_EMAIL') ?: '';
$TO_NAME  = getenv('CONTACT_TO_NAME')  ?: 'PinterPal Support';
$FROM_EMAIL = getenv('CONTACT_FROM_EMAIL') ?: ($TO_EMAIL ?: 'no-reply@pinterpal.com');
$FROM_NAME  = getenv('CONTACT_FROM_NAME')  ?: 'PinterPal';

$SENDGRID_KEY   = getenv('SENDGRID_API_KEY') ?: '';
$MAILGUN_KEY    = getenv('MAILGUN_API_KEY') ?: '';
$MAILGUN_DOMAIN = getenv('MAILGUN_DOMAIN') ?: '';

$SOC_IG  = getenv('CONTACT_INSTAGRAM_URL') ?: '';
$SOC_LI  = getenv('CONTACT_LINKEDIN_URL')  ?: '';
$SOC_X   = getenv('CONTACT_TWITTER_URL')   ?: '';
$SOC_TT  = getenv('CONTACT_TIKTOK_URL')    ?: '';

$PUBLIC_PHONE = '+31 6 36270282';
$PUBLIC_EMAIL = $TO_EMAIL ?: 'info@pinterpal.com';
$PUBLIC_ADDR  = 'Den Haag';

// ---- mail providers ----
function send_via_sendgrid($toEmail,$toName,$fromEmail,$fromName,$replyEmail,$replyName,$subject,$text,$html,$apiKey){
  $payload = [
    'personalizations' => [[ 'to' => [[ 'email'=>$toEmail, 'name'=>$toName ]], 'subject' => $subject ]],
    'from'     => [ 'email'=>$fromEmail, 'name'=>$fromName ],
    'reply_to' => [ 'email'=>$replyEmail, 'name'=>$replyName ],
    'content'  => [
      ['type'=>'text/plain','value'=>$text],
      ['type'=>'text/html','value'=>$html],
    ],
  ];
  $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>true,
    CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$apiKey,'Content-Type: application/json'],
    CURLOPT_POSTFIELDS=>json_encode($payload, JSON_UNESCAPED_SLASHES),
    CURLOPT_CONNECTTIMEOUT=>5, CURLOPT_TIMEOUT=>15,
  ]);
  $res=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); $err=curl_error($ch); curl_close($ch);
  if ($res===false || $code<200 || $code>=300){ elog("sendgrid FAIL http=$code err=$err body=$res"); return false; }
  return true;
}
function send_via_mailgun($toEmail,$toName,$fromEmail,$fromName,$replyEmail,$replyName,$subject,$text,$html,$apiKey,$domain){
  $endpoint = "https://api.mailgun.net/v3/{$domain}/messages";
  $post = [
    'from' => "{$fromName} <{$fromEmail}>",
    'to'   => "{$toName} <{$toEmail}>",
    'h:Reply-To' => "{$replyName} <{$replyEmail}>",
    'subject' => $subject, 'text' => $text, 'html' => $html,
  ];
  $ch = curl_init($endpoint);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>true, CURLOPT_USERPWD=>'api:'.$apiKey,
    CURLOPT_POSTFIELDS=>$post, CURLOPT_CONNECTTIMEOUT=>5, CURLOPT_TIMEOUT=>15,
  ]);
  $res=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); $err=curl_error($ch); curl_close($ch);
  if ($res===false || $code<200 || $code>=300){ elog("mailgun FAIL http=$code err=$err body=$res"); return false; }
  return true;
}

// ---- handle request ----
$sentOk = isset($_GET['sent']) && $_GET['sent']==='1';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $_SESSION['contact_started_at'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name']    ?? '');
  $email   = trim($_POST['email']   ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  $token   = $_POST['csrf'] ?? '';
  $hp      = trim($_POST['website'] ?? ''); // honeypot

  if ($hp !== '') { $error = 'Ongeldige inzending.'; elog('honeypot'); }
  elseif (!$token || !hash_equals($_SESSION['csrf_contact'] ?? '', $token)) { $error='Formulier verlopen. Probeer opnieuw.'; }
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error='Vul een geldig e-mailadres in.'; }
  elseif ($name==='' || $subject==='' || $message==='') { $error='Vul alle velden in.'; }
  else {
    $started = $_SESSION['contact_started_at'] ?? 0;
    if ($started && (time()-$started) < 3)       { $error='Te snel verstuurd. Probeer nog eens.'; }
    elseif (!check_rate_limit(600,3))            { $error='Je hebt te vaak gemaild. Probeer later opnieuw.'; }
  }

  if ($error==='') {
    if ($TO_EMAIL==='') {
      $error='E-mail is nog niet geconfigureerd.'; elog('CONTACT_TO_EMAIL missing');
    } else {
      $cleanSub = mb_substr($subject, 0, 160);
      $text = "Nieuw bericht via contactformulier\n\nNaam: $name\nE-mail: $email\nOnderwerp: $cleanSub\n\nBericht:\n$message\n\nURL: ".base_url().$_SERVER['REQUEST_URI']."\n";
      $html = "<p><strong>Nieuw bericht via contactformulier</strong></p>"
            . "<p><b>Naam:</b> ".htmlspecialchars($name)."<br>"
            . "<b>E-mail:</b> ".htmlspecialchars($email)."<br>"
            . "<b>Onderwerp:</b> ".htmlspecialchars($cleanSub)."</p>"
            . "<p><b>Bericht:</b><br>".nl2br(htmlspecialchars($message))."</p>"
            . "<p><small>URL: ".htmlspecialchars(base_url().$_SERVER['REQUEST_URI'])."</small></p>";

      $ok=false;
      if ($SENDGRID_KEY)            $ok = send_via_sendgrid($TO_EMAIL,$TO_NAME,$FROM_EMAIL,$FROM_NAME,$email,$name,$cleanSub,$text,$html,$SENDGRID_KEY);
      elseif ($MAILGUN_KEY && $MAILGUN_DOMAIN) $ok = send_via_mailgun($TO_EMAIL,$TO_NAME,$FROM_EMAIL,$FROM_NAME,$email,$name,$cleanSub,$text,$html,$MAILGUN_KEY,$MAILGUN_DOMAIN);
      else { $error='E-mail is nog niet geconfigureerd.'; elog('no provider'); }

      if ($ok) { unset($_SESSION['csrf_contact']); header('Location: /contact.php?sent=1', true, 302); exit; }
      else { $error='Versturen mislukt. Probeer het later opnieuw.'; }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact - PinterPal</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    :root { --gap: 1rem; --maxw: 1100px; }
    body { margin:0; font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif; color:#111; }

    /* Header (match assistance.php) */
    .header { padding: .75rem var(--gap) 0; text-align:center; }
    .header-logo { max-width: 56px; height:auto; }
    .logo-boven img { max-width: 220px; width: 60vw; height:auto; margin: 0 auto; display:block; }
    .navbar { position:sticky; top:0; z-index:10; display:flex; gap:.25rem; flex-wrap:wrap; justify-content:center; padding:.5rem; border-top:4px solid var(--secondary-color); border-bottom:4px solid var(--secondary-color); background:var(--primary-color); }
    .navbar a { display:inline-block; padding:.5rem .75rem; border-radius:.5rem; text-decoration:none; font-weight:600; }
    .navbar a:hover, .navbar a.active { background:rgba(255,255,255,.12); color: var(--font-color); }

    .content { max-width: var(--maxw); margin: 0 auto; padding: 1rem var(--gap) 2rem; }

    /* Top contact info grid */
    .contact-cards {
      display:grid; grid-template-columns: 1fr; gap: var(--gap);
      margin: 1.25rem 0 1.5rem; padding: 1rem;
      background-color: rgba(10,112,130,.05);
      border-radius: .75rem;
    }
    .contact-card {
      background: var(--secondary-color);
      border-radius:.75rem; padding: 1rem; text-align:center;
      box-shadow: 0 1px 3px rgba(0,0,0,.12);
    }
    .contact-card h3 { margin:.25rem 0 .25rem; font-size:1.05rem; }
    .contact-card p { margin:.25rem 0 0; }
    .contact-card a { text-decoration:none; font-weight:700; }

    /* Wider form container */
    .form-wrap {
      max-width: 900px; /* breder dan cards */
      margin: 0 auto; padding: 1.25rem;
      background: #0a7082; color:#000;
      border: 2px solid #ffc107; border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,.2);
    }
    form label { font-weight:700; margin: .5rem 0 .25rem; display:block; }
    form input, form textarea {
      width:100%; padding:.8rem; border:2px solid #0a7082; border-radius:6px;
      background:#fff;
    }
    .btn {
      display:inline-block; margin-top:.75rem; padding:.8rem 1.2rem;
      background:#8c52ff; color:#fff; border:0; border-radius:6px; font-weight:700; cursor:pointer;
    }
    .ok { color:#0a0; font-weight:700; }
    .err { color:#b00020; font-weight:700; }
    .hp { position:absolute; left:-9999px; top:-9999px; visibility:hidden; }

    @media (min-width: 640px) {
      .contact-cards { grid-template-columns: repeat(2, minmax(0,1fr)); padding: 1.25rem; }
    }
    @media (min-width: 1024px) {
      .contact-cards { grid-template-columns: repeat(4, minmax(0,1fr)); }
    }
  </style>
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

<!-- Navigation -->
<nav class="navbar" aria-label="Hoofdnavigatie">
  <a href="index.php">HOME</a>
  <a href="pinterpalbot.php">PINTERPAL BOT</a>
  <a href="iframe.php">TRY ME</a>
  <a href="pricing.php">PRICING</a>
  <a href="assistance.php">ASSISTANCE</a>
  <a href="about.php">ABOUT US</a>
  <a href="contact.php" class="active" aria-current="page">CONTACT</a>
</nav>

<main class="content">
  <!-- Top: Socials / Phone / Email / Address -->
  <section class="contact-cards">
    <div class="contact-card">
      <h3>📞 Phone</h3>
      <p><a href="tel:+31636270282"><?= htmlspecialchars($PUBLIC_PHONE) ?></a></p>
    </div>
    <div class="contact-card">
      <h3>✉️ Email</h3>
      <p><a href="mailto:<?= htmlspecialchars($PUBLIC_EMAIL) ?>"><?= htmlspecialchars($PUBLIC_EMAIL) ?></a></p>
    </div>
    <div class="contact-card">
      <h3>📍 Address</h3>
      <p><?= htmlspecialchars($PUBLIC_ADDR) ?></p>
    </div>
    <div class="contact-card">
      <h3>🔗 Socials</h3>
      <p style="line-height:1.6">
        <?php if ($SOC_IG): ?><a href="<?= htmlspecialchars($SOC_IG) ?>" target="_blank" rel="noopener">Instagram</a><br><?php endif; ?>
        <?php if ($SOC_LI): ?><a href="<?= htmlspecialchars($SOC_LI) ?>" target="_blank" rel="noopener">LinkedIn</a><br><?php endif; ?>
        <?php if ($SOC_X):  ?><a href="<?= htmlspecialchars($SOC_X)  ?>" target="_blank" rel="noopener">X / Twitter</a><br><?php endif; ?>
        <?php if ($SOC_TT): ?><a href="<?= htmlspecialchars($SOC_TT) ?>" target="_blank" rel="noopener">TikTok</a><?php endif; ?>
        <?php if (!$SOC_IG && !$SOC_LI && !$SOC_X && !$SOC_TT): ?>
          <span style="opacity:.8">Volg ons op social — links coming soon</span>
        <?php endif; ?>
      </p>
    </div>
  </section>

  <!-- Brede form -->
  <section class="form-wrap">
    <h2>Neem contact op</h2>

    <?php if ($sentOk): ?>
      <p class="ok">Thanks! Je bericht is verstuurd. We nemen zo snel mogelijk contact met je op.</p>
      <p><a href="/">Terug naar home</a></p>
    <?php else: ?>
      <?php if (!empty($error)): ?>
        <p class="err"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post" action="/contact.php" novalidate>
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input class="hp" type="text" name="website" autocomplete="off" tabindex="-1">

        <label for="name">Naam</label>
        <input id="name" name="name" type="text" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label for="email">E-mailadres</label>
        <input id="email" name="email" type="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="subject">Onderwerp</label>
        <input id="subject" name="subject" type="text" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">

        <label for="message">Bericht</label>
        <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button class="btn" type="submit">Versturen</button>
      </form>
    <?php endif; ?>
  </section>
</main>

<footer class="contact-info" style="text-align:center; padding:1.5rem 1rem;">
  <p>
    <strong>KVK:</strong> 96433647<br>
    <strong>Address:</strong> Den Haag<br>
    <strong>Telephone:</strong> <?= htmlspecialchars($PUBLIC_PHONE) ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($PUBLIC_EMAIL) ?>
  </p>
  <p><a href="/terms-coditions.php" style="color: inherit; text-decoration: none;">📄 Terms &amp; Conditions</a></p>
</footer>

<script>
  // Active link highlight
  document.addEventListener('DOMContentLoaded', function () {
    const currentPage = (window.location.pathname.split('/').pop() || 'index.php').toLowerCase();
    document.querySelectorAll('.navbar a').forEach(a => {
      a.classList.toggle('active', a.getAttribute('href').toLowerCase() === currentPage);
    });
  });
</script>
</body>
</html>
