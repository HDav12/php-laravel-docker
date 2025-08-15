<?php
declare(strict_types=1);
require __DIR__ . '/../bootstrap.php';

$mollie = mollie();
$id = $_POST['id'] ?? null;
if (!$id) { http_response_code(400); exit('no id'); }

try {
  $payment = $mollie->payments->get($id);
  $orderId = $payment->metadata->order_id ?? null;

  if ($payment->isPaid()) {
    // TODO: markeer $orderId als betaald
  } elseif ($payment->isCanceled() || $payment->isExpired() || $payment->isFailed()) {
    // TODO: markeer als mislukt/geannuleerd
  }
  http_response_code(200);
  echo 'ok';
} catch (Throwable $e) {
  error_log('webhook: ' . $e->getMessage());
  http_response_code(500);
  echo 'error';
}
