<?php
require __DIR__.'/bootstrap.php';; // bovenaan, na require

// ... na $payment = $mollie->payments->create([...]);
$_SESSION['last_payment_id'] = $payment->id;  // onthouden voor bedankt.php
header('Location: ' . $payment->getCheckoutUrl());
exit;



declare(strict_types=1);
require __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('POST'); }

$mollie = mollie();

$amount      = $_POST['amount'] ?? '10.00';
$description = $_POST['description'] ?? 'Bestelling';
$orderId     = $_POST['orderId'] ?? uniqid('order_', true);

try {
  $payment = $mollie->payments->create([
    'amount'      => ['currency' => 'EUR', 'value' => normalize_amount($amount)],
    'description' => $description,
    'redirectUrl' => base_url() . '/bedankt.php?orderId=' . urlencode($orderId),
    'webhookUrl'  => base_url() . '/webhook.php',
    'metadata'    => ['order_id' => $orderId],
  ]);
  header('Location: ' . $payment->getCheckoutUrl());
  exit;
} catch (Throwable $e) {
  error_log('create-payment: ' . $e->getMessage());
  http_response_code(500);
  echo 'Betaling starten faalde.';
}
