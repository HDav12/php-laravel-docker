<?php
// --- Settings ---
// Zet deze ENV in Render -> Environment (aanrader):
// APP_HOST = pinterpal.com
$wantHost = getenv('APP_HOST') ?: ($_SERVER['HTTP_HOST'] ?? 'pinterpal.com');

// Detect HTTPS achter proxy
$https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
      || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

$host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? $wantHost);

// Forceer canonical host + https
if ($host !== $wantHost || !$https) {
    $target = 'https://' . $wantHost . ($_SERVER['REQUEST_URI'] ?? '/');
    // behoud POST bij redirect
    $code = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 307 : 301;
    if (!headers_sent()) { header('Location: '.$target, true, $code); exit; }
    echo '<script>location.href='.json_encode($target).'</script>'; exit;
}

// Session cookie zo instellen dat hij ook na Mollie redirect blijft
$cookieDomain = '.' . preg_replace('/^www\./i', '', $wantHost);
$lifetimeDays = (int)(getenv('SESSION_DAYS') ?: 14);
$lifetime     = 60 * 60 * 24 * max(1, $lifetimeDays);

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',
    'domain'   => $cookieDomain,   // werkt op zowel apex als www.
    'secure'   => true,            // alleen via HTTPS
    'httponly' => true,
    'samesite' => 'Lax',           // top-level navigations (Mollie -> terug) sturen cookie wél mee
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('PPSESS'); // nette naam voor je cookie
    session_start();
}
