<?php
session_start();
function elog($m){ @file_put_contents('php://stderr', "[app] ".str_replace(["\r","\n"],' ',$m)."\n"); }

function api_key() {
    return (getenv('APP_ENV') === 'production') ? (getenv('MOLLIE_API_KEY_LIVE') ?: '') : (getenv('MOLLIE_API_KEY_TEST') ?: '');
}
$apiKey = api_key();
if ($apiKey === '') { echo "API key mist."; exit; }

// valideer state
$stateParam = $_GET['state'] ?? '';
$stateSess  = $_SESSION['mollie_state'] ?? '';
$stateOk    = ($stateParam && $stateSess && hash_equals($stateSess, $stateParam));

// payment id
$paymentId = $_GET['id'] ?? ($_SESSION['last_payment_id'] ?? null);
if (!$paymentId) { echo "Geen payment id."; exit; }

// haal status op bij Mollie (vertrouw nooit de URL alleen)
$ch = curl_init("https://api.mollie.com/v2/payments/" . urlencode($paymentId));
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => [
    'Authorization: Bearer ' . $apiKey,
    'User-Agent: PinterPal/1.0 (+return)',
  ],
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT        => 15,
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($res === false || $code < 200 || $code >= 300) { echo "Kon status niet ophalen."; exit; }

$data    = json_decode($res, true);
$status  = $data['status'] ?? 'unknown';
$meta    = $data['metadata'] ?? [];
$statePM = (string)($meta['state'] ?? '');
$stateOk = $stateOk && hash_equals($statePM, $stateSess);

// UI (no activation here)
?><!doctype html>
<html><head><meta charset="utf-8"><title>Betaling status</title>
<style>body{font-family:system-ui;padding:2rem} .ok{color:#0a0}.bad{color:#a00}.wait{color:#aa0}.warn{color:#a60}</style></head>
<body>
<h1>Betaling</h1>
<p>Payment ID: <code><?=htmlspecialchars($paymentId)?></code></p>
<p>State check: <?= $stateOk ? '<span class="ok">OK</span>' : '<span class="warn">mismatch/expired</span>' ?></p>
<p>Status:
<?php if ($status==='paid'): ?>
  <strong class="ok">Betaald ✅</strong>
<?php elseif (in_array($status, ['open','pending','authorized'])): ?>
  <strong class="wait">Open / In afwachting ⏳</strong>
<?php else: ?>
  <strong class="bad"><?=htmlspecialchars($status)?> ❌</strong>
<?php endif; ?>
</p>

<?php if ($status==='paid'): ?>
  <p>Thanks! Je betaling is ontvangen. Je account wordt geactiveerd zodra de webhook verwerkt is.</p>
  <p><a href="/index.php">Terug naar de app →</a></p>
<?php else: ?>
  <p><a href="/payment.php?plan=premium<?= isset($_SESSION['user_id']) ? '&user_id='.(int)$_SESSION['user_id'] : '' ?>">Opnieuw proberen</a></p>
<?php endif; ?>
</body></html>
