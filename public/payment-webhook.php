<?php
// Server-side bevestiging door Mollie.
// Moet altijd 200 teruggeven (anders retryt Mollie). Activeer/upgrade ALLEEN hier.
include __DIR__ . '/database.php';

function elog($m){ @file_put_contents('php://stderr', "[app] ".str_replace(["\r","\n"],' ',$m)."\n"); }
function api_key() {
    return (getenv('APP_ENV') === 'production') ? (getenv('MOLLIE_API_KEY_LIVE') ?: '') : (getenv('MOLLIE_API_KEY_TEST') ?: '');
}
$apiKey = api_key();

// ==== webhook secret check ====
$expected = (string)(getenv('MOLLIE_WEBHOOK_SECRET') ?: '');
$given    = (string)($_GET['secret'] ?? '');
if ($expected !== '' && (!hash_equals($expected, $given))) {
    // geef nog steeds 200 zodat Mollie niet blijft retryn, maar doe niks
    elog('webhook secret mismatch');
    http_response_code(200); echo 'ok'; exit;
}

$paymentId = $_POST['id'] ?? null;
if (!$paymentId || $apiKey==='') { http_response_code(200); echo 'ok'; exit; }

// ==== payment ophalen bij Mollie ====
$ch = curl_init("https://api.mollie.com/v2/payments/" . urlencode($paymentId));
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => [
    'Authorization: Bearer ' . $apiKey,
    'User-Agent: PinterPal/1.0 (+webhook)',
  ],
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT        => 15,
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($res === false || $code < 200 || $code >= 300) { http_response_code(200); echo 'ok'; exit; }
$data = json_decode($res, true);

// ==== validatie ====
$status   = $data['status'] ?? 'unknown';
$amount   = (string)($data['amount']['value'] ?? '');
$currency = (string)($data['amount']['currency'] ?? '');
$meta     = $data['metadata'] ?? [];
$plan     = (string)($meta['plan'] ?? '');

// server-side prijslijst
$plans = [
  'premium' => ['currency'=>'EUR', 'value'=>'29.90'],
  // 'basic' => ['currency'=>'EUR', 'value'=>'9.90'],
];
$expected = $plans[$plan] ?? null;

// alleen door als paid + bedrag/currency klopt precies
if ($status !== 'paid' || !$expected || $expected['value'] !== $amount || $expected['currency'] !== $currency) {
    elog("webhook reject id=$paymentId status=$status amount=$amount/$currency plan=$plan");
    http_response_code(200); echo 'ok'; exit;
}

$userId    = isset($meta['user_id'])    ? (int)$meta['user_id']    : null;
$companyId = isset($meta['company_id']) ? (int)$meta['company_id'] : null;

// ==== DB update ====
if ($companyId) {
    // update company plan/status (pas kolomnamen aan jouw schema)
    $planVal = $plan; $statusVal = 'paid'; $cid = $companyId;
    $sql = "UPDATE dbo.Companies SET payment_plan = ?, payment_status = ? WHERE id = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [ &$planVal, &$statusVal, &$cid ]);
    if ($stmt && sqlsrv_execute($stmt)) { sqlsrv_free_stmt($stmt); }
} elseif ($userId) {
    // upgrade user role (pas aan op jouw schema)
    $newRole = 'premium_user'; $uid = $userId;
    $sql = "UPDATE dbo.Users SET role = ? WHERE id = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [ &$newRole, &$uid ]);
    if ($stmt && sqlsrv_execute($stmt)) { sqlsrv_free_stmt($stmt); }
}

sqlsrv_close($conn);
http_response_code(200);
echo 'ok';
