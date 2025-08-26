<?php
// contact.php
// — veilig contactformulier met SendGrid/Mailgun, zonder Composer —

ini_set('display_errors','0');
ini_set('log_errors','1');
ini_set('error_log','php://stderr');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_name('__Host-ppsid');
    session_start();
}

/* ---------- helpers ---------- */
function elog($m){ @file_put_contents('php://stderr', "[contact] ".str_replace(["\r","\n"],' ',(string)$m)."\n"); }
function base_url(){
    $https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $proto = $https ? 'https' : 'http';
    $host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    return $proto.'://'.$host;
}
function new_token(){ return bin2hex(random_bytes(16)); }
function csrf_token(){
    if (empty($_SESSION['csrf_contact'])) $_SESSION['csrf_contact'] = new_token();
    return $_SESSION['csrf_contact'];
}
function check_rate_limit($windowSec=600, $max=3){
    $now = time();
    $_SESSION['contact_times'] = array_values(array_filter(($_SESSION['contact_times'] ?? []), fn($t)=>($now-$t)<$windowSec));
    if (count($_SESSION['contact_times']) >= $max) return false;
    $_SESSION['contact_times'][] = $now;
    return true;
}

/* ---------- config via ENV ---------- */
$TO_EMAIL = getenv('CONTACT_TO_EMAIL') ?: '';
$TO_NAME  = getenv('CONTACT_TO_NAME')  ?: 'Support';
$FROM_EMAIL = getenv('CONTACT_FROM_EMAIL') ?: ($TO_EMAIL ?: 'no-reply@example.com');
$FROM_NAME  = getenv('CONTACT_FROM_NAME')  ?: 'PinterPal';

$SENDGRID_KEY = getenv('SENDGRID_API_KEY') ?: '';
$MAILGUN_KEY  = getenv('MAILGUN_API_KEY') ?: '';
$MAILGUN_DOMAIN = getenv('MAILGUN_DOMAIN') ?: '';

/* ---------- mail providers ---------- */
function send_via_sendgrid($toEmail,$toName,$fromEmail,$fromName,$replyEmail,$replyName,$subject,$text,$html,$apiKey){
    $payload = [
        'personalizations' => [[
            'to' => [[ 'email'=>$toEmail, 'name'=>$toName ]],
            'subject' => $subject,
        ]],
        'from'  => [ 'email'=>$fromEmail, 'name'=>$fromName ],
        'reply_to' => [ 'email'=>$replyEmail, 'name'=>$replyName ],
        'content' => [
            ['type'=>'text/plain','value'=>$text],
            ['type'=>'text/html','value'=>$html],
        ],
    ];
    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer '.$apiKey,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($res === false || ($code < 200 || $code >= 300)) {
        elog("sendgrid FAIL http=$code err=$err body=$res");
        return false;
    }
    return true;
}
function send_via_mailgun($toEmail,$toName,$fromEmail,$fromName,$replyEmail,$replyName,$subject,$text,$html,$apiKey,$domain){
    $endpoint = "https://api.mailgun.net/v3/{$domain}/messages";
    $post = [
        'from'    => "{$fromName} <{$fromEmail}>",
        'to'      => "{$toName} <{$toEmail}>",
        'h:Reply-To' => "{$replyName} <{$replyEmail}>",
        'subject' => $subject,
        'text'    => $text,
        'html'    => $html,
    ];
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_USERPWD        => 'api:'.$apiKey,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($res === false || ($code < 200 || $code >= 300)) {
        elog("mailgun FAIL http=$code err=$err body=$res");
        return false;
    }
    return true;
}

/* ---------- POST handling ---------- */
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

    // basic validation
    if ($hp !== '') {
        elog('honeypot hit'); // stomme bots
        $error = 'Ongeldige inzending.';
    } elseif (!$token || !hash_equals($_SESSION['csrf_contact'] ?? '', $token)) {
        $error = 'Formulier verlopen. Probeer opnieuw.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Vul een geldig e-mailadres in.';
    } elseif ($name === '' || $subject === '' || $message === '') {
        $error = 'Vul alle velden in.';
    } else {
        // min. tijd op pagina (anti-bot)
        $started = $_SESSION['contact_started_at'] ?? 0;
        if ($started && (time() - $started) < 3) {
            $error = 'Te snel verstuurd. Probeer nog eens.';
        } elseif (!check_rate_limit(600, 3)) {
            $error = 'Je hebt te vaak gemaild. Probeer later opnieuw.';
        }
    }

    if ($error === '') {
        if ($TO_EMAIL === '') {
            elog('CONFIG missing CONTACT_TO_EMAIL');
            $error = 'E-mail is nog niet geconfigureerd. Neem contact op met support.';
        } else {
            // opmaak
            $cleanSub = mb_substr($subject, 0, 160);
            $text = "Nieuw bericht via contactformulier\n\n"
                  . "Naam: $name\n"
                  . "E-mail: $email\n"
                  . "Onderwerp: $cleanSub\n\n"
                  . "Bericht:\n$message\n\n"
                  . "URL: ".base_url().$_SERVER['REQUEST_URI']."\n";
            $html = "<p><strong>Nieuw bericht via contactformulier</strong></p>"
                  . "<p><b>Naam:</b> ".htmlspecialchars($name)."<br>"
                  . "<b>E-mail:</b> ".htmlspecialchars($email)."<br>"
                  . "<b>Onderwerp:</b> ".htmlspecialchars($cleanSub)."</p>"
                  . "<p><b>Bericht:</b><br>".nl2br(htmlspecialchars($message))."</p>"
                  . "<p><small>URL: ".htmlspecialchars(base_url().$_SERVER['REQUEST_URI'])."</small></p>";

            $ok = false;
            if ($SENDGRID_KEY) {
                $ok = send_via_sendgrid($TO_EMAIL,$TO_NAME,$FROM_EMAIL,$FROM_NAME,$email,$name,$cleanSub,$text,$html,$SENDGRID_KEY);
            } elseif ($MAILGUN_KEY && $MAILGUN_DOMAIN) {
                $ok = send_via_mailgun($TO_EMAIL,$TO_NAME,$FROM_EMAIL,$FROM_NAME,$email,$name,$cleanSub,$text,$html,$MAILGUN_KEY,$MAILGUN_DOMAIN);
            } else {
                elog('No provider configured (SENDGRID_API_KEY or MAILGUN_*)');
                $error = 'E-mail is nog niet geconfigureerd. Neem contact op met support.';
            }

            if ($ok) {
                // reset token zodat refresh niet nogmaals kan versturen
                unset($_SESSION['csrf_contact']);
                header('Location: /contact.php?sent=1', true, 302);
                exit;
            } else {
                $error = 'Versturen mislukt. Probeer het later opnieuw.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contact</title>
<link rel="stylesheet" href="css/style.css">
<style>
  .wrap{max-width:720px;margin:5vh auto;padding:2rem;background:#0a7082;border:2px solid #ffc107;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,.2);color:#000}
  label{font-weight:700;margin:8px 0 4px;display:block}
  input,textarea{width:100%;padding:.8rem;border:2px solid #0a7082;border-radius:6px}
  button{margin-top:12px;background:#8c52ff;color:#fff;border:0;border-radius:6px;padding:.8rem 1.2rem;font-weight:700;cursor:pointer}
  .ok{color:#0a0;font-weight:700}
  .err{color:#b00020;font-weight:700}
  .hp{position:absolute;left:-9999px;top:-9999px;visibility:hidden}
</style>
</head>
<body>
  <div class="header">
    <a href="index.php"><h1>PINTERPAL</h1></a>
    <div class="login-signup">
      <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    </div>
  </div>

  <div class="wrap">
    <h2>Contact</h2>

    <?php if ($sentOk): ?>
      <p class="ok">Thanks! Je bericht is verstuurd. We nemen zo snel mogelijk contact met je op.</p>
      <p><a href="/">Terug naar home</a></p>
    <?php else: ?>
      <?php if (!empty($error)): ?>
        <p class="err"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post" action="/contact.php" novalidate>
        <!-- CSRF -->
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <!-- Honeypot -->
        <input class="hp" type="text" name="website" autocomplete="off" tabindex="-1">

        <label for="name">Naam</label>
        <input id="name" name="name" type="text" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label for="email">E-mailadres</label>
        <input id="email" name="email" type="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="subject">Onderwerp</label>
        <input id="subject" name="subject" type="text" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">

        <label for="message">Bericht</label>
        <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit">Versturen</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
