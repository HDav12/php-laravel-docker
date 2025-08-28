<?php
// ===== Canonical host & HTTPS =====
// Zet in Render ENV: APP_HOST=pinterpal.com
$wantHost = getenv('APP_HOST') ?: ($_SERVER['HTTP_HOST'] ?? 'pinterpal.com');

$https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
      || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

$host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? $wantHost);

// Force https + gewenste host
if ($host !== $wantHost || !$https) {
    $target = 'https://' . $wantHost . ($_SERVER['REQUEST_URI'] ?? '/');
    $code = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 307 : 301;
    if (!headers_sent()) { header('Location: '.$target, true, $code); exit; }
    echo '<script>location.href='.json_encode($target).'</script>'; exit;
}

// ===== Session cookie strak instellen =====
$cookieDomain = (stripos($wantHost,'localhost') !== false)
    ? '' : '.' . preg_replace('/^www\./i', '', $wantHost);
$lifetimeDays = (int)(getenv('SESSION_DAYS') ?: 14);
$lifetime     = 60 * 60 * 24 * max(1, $lifetimeDays);

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',
    'domain'   => $cookieDomain ?: null, // geen domain op localhost
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax', // belangrijk voor terugkeer vanaf Mollie
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('PPSESS');
    session_start();
}

// ===== Helper voor nette errors =====
if (!function_exists('redirect_fail')) {
  function redirect_fail($ctx,$details=null,$http=302){
    @file_put_contents('php://stderr', "[app] FAIL[$ctx] ".json_encode($details)."\n");
    $to = '/error.php?code='.rawurlencode($ctx);
    if (!headers_sent()) { header('Location: '.$to, true, $http); exit; }
    echo '<script>location.href='.json_encode($to).'</script>'; exit;
  }
}

// ===== DB connect ($conn) =====
require_once __DIR__ . '/database.php';
