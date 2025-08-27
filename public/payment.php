<?php
session_start();

function elog($m){ @file_put_contents('php://stderr', "[app] ".str_replace(["\r","\n"],' ',$m)."\n"); }

// ==== force HTTPS (achter proxy ok) ====
function base_url() {
    $proto = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')) ? 'https' : 'http';
    $host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    return $proto.'://'.$host;
}
if (strpos(base_url(), 'http://') === 0) {
    // als je strict https wil forceren:
    // header('Location: '.preg_replace('#^http:#','https:', base_url().$_SERVER['REQUEST_URI']), true, 301);
    // exit;
}

// ==== API key kiezen (test vs live) ====
function api_key() {
    return (getenv('APP_ENV') === 'production') ? (getenv('MOLLIE_API_KEY_LIVE') ?: '') : (getenv('MOLLIE_API_KEY_TEST') ?: '');
}
$apiKey = api_key();
if ($apiKey === '') { http_response_code(500); echo "Mollie API key mist."; exit; }

// (LET OP: GEEN profileId gebruiken bij API key auth)
// $profileId = getenv('MOLLIE_PROFILE_ID') ?: null;

// ==== user context ====
$userId    = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$role      = $_SESSION['user_role'] ?? null;

// we accepteren optioneel company_id uit query, maar alleen meenemen als ingelogd
$companyId = isset($_GET['company_id']) ? (int)$_GET['company_id'] : null;
if (!$userId && !$companyId) {
    http_response_code(401); echo "Login vereist om te betalen."; exit;
}

// ==== plan beveiligd ====
$planReq = $_GET['plan'] ?? 'premium';
$plans = [
  'premium' => ['currency'=>'EUR', 'value'=>'29.90', 'desc'=>'PinterPal premium abonnement'],
  // 'basic' => ['currency'=>'EUR', 'value'=>'9.90', 'desc'=>'PinterPal basic abonnement'],
];
if (!isset($plans[$planReq])) { http_response_code(400); echo "Onbekend plan."; exit; }
$plan     = $planReq;
$currency = $plans[$plan]['currency'];
$amount   = $plans[$plan]['value'];
$desc     = $plans[$plan]['desc'];

// ==== state token (CSRF-achtig) ====
$state = bin2hex(random_bytes(16));
$_SESSION['mollie_state'] = $state;

// URLs
$redirectUrl = base_url() . "/payment-return.php?state=" . urlencode($state);
$secretQS    = http_build_query(['secret' => (string)(getenv('MOLLIE_WEBHOOK_SECRET') ?: '')]);
$webhookUrl  = base_url() . "/payment-webhook.php" . ($secretQS ? "?$secretQS" : "");

// metadata
$metadata = [
  'state'      => $state,
  'plan'       => $plan,
  'user_id'    => $userId,
  'company_id' => $companyId,
];

// optioneel: locale (API hint)
$locale = getenv('PAYMENT_LOCALE') ?: 'nl_NL';

// ==== call Mollie ====
$payload = [
  'amount'      => ['currency' => $currency, 'value' => $amount],
  'description' => $desc,
  'redirectUrl' => $redirectUrl,
  'webhookUrl'  => $webhookUrl,
  'metadata'    => $metadata,
  'locale'      => $locale,
  // 'method'   => ['ideal','creditcard'], // optioneel beperken
];
// GEEN $payload['profileId'] hier!

$ch = curl_init('https://api.mollie.com/v2/payments');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_HTTPHEADER     => [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
    'User-Agent: PinterPal/1.0 (+payments)',
  ],
  CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT        => 15,
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

if ($res === false || $code < 200 || $code >= 300) {
  elog("MOLLIE create FAIL http=$code err=$err body=$res");
  http_response_code(502); echo "Kon payment niet aanmaken."; exit;
}
$data = json_decode($res, true);
$checkout  = $data['_links']['checkout']['href'] ?? null;
$paymentId = $data['id'] ?? null;
if (!$checkout || !$paymentId) {
  elog("MOLLIE create PARSE FAIL: $res");
  http_response_code(502); echo "Geen checkout link ontvangen."; exit;
}

// bewaar laatste payment id
$_SESSION['last_payment_id'] = $paymentId;

// Redirect naar Mollie checkout
header("Location: $checkout", true, 302);
exit;
