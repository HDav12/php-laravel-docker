<?php
session_start();
function elog($m){ @file_put_contents('php://stderr', "[app] ".str_replace(["\r","\n"],' ',$m)."\n"); }

function api_key() {
  return (getenv('APP_ENV') === 'production') ? (getenv('MOLLIE_API_KEY_LIVE') ?: '') : (getenv('MOLLIE_API_KEY_TEST') ?: '');
}
$apiKey = api_key();

$stateParam = $_GET['state'] ?? '';
$stateSess  = $_SESSION['mollie_state'] ?? '';
$stateOk    = ($stateParam && $stateSess && hash_equals($stateSess, $stateParam));

$paymentId = $_GET['id'] ?? ($_SESSION['last_payment_id'] ?? null);

$ui = [
  'ok'    => false,
  'title' => 'Betaling',
  'msg'   => '',
  'status'=> 'unknown',
  'amount'=> '',
  'currency' => '',
  'plan'  => '',
  'paymentId' => $paymentId ?: '',
  'stateOk'   => $stateOk,
];

if ($apiKey === '') {
  $ui['title'] = 'Configuratie fout';
  $ui['msg']   = 'Mollie API-sleutel ontbreekt.';
} elseif (!$paymentId) {
  $ui['title'] = 'Geen payment ID';
  $ui['msg']   = 'We konden geen betaling herkennen.';
} else {
  // status ophalen bij Mollie
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
  $err  = curl_error($ch);
  curl_close($ch);

  if ($res === false || $code < 200 || $code >= 300) {
    $ui['title'] = 'Status onbekend';
    $ui['msg']   = 'Kon de betaalstatus niet ophalen. Probeer het zo nog eens.';
    elog("MOLLIE return FAIL http=$code err=$err body=$res");
  } else {
    $data = json_decode($res, true);
    $ui['status']   = $data['status'] ?? 'unknown';
    $ui['amount']   = $data['amount']['value'] ?? '';
    $ui['currency'] = $data['amount']['currency'] ?? '';
    $ui['plan']     = $data['metadata']['plan'] ?? '';
    $statePM        = (string)($data['metadata']['state'] ?? '');
    $ui['stateOk']  = $ui['stateOk'] && $statePM && hash_equals($statePM, $stateSess);

    if ($ui['status'] === 'paid') {
      $ui['ok']    = true;
      $ui['title'] = 'Betaling geslaagd';
      $ui['msg']   = 'Thanks! Je betaling is ontvangen. Je account wordt direct/na webhook verwerkt.';
      // cleanup polling teller
      $clearPoll = true;
    } elseif (in_array($ui['status'], ['open','pending','authorized'])) {
      $ui['title'] = 'Betaling in verwerking';
      $ui['msg']   = 'We zijn je betaling aan het verwerken. Deze pagina ververst automatisch.';
    } else {
      $ui['title'] = 'Betaling niet gelukt';
      $ui['msg']   = 'De betaling is niet afgerond. Je kunt het opnieuw proberen.';
    }
  }
}
?>
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($ui['title']) ?> - PinterPal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --pp-teal:#0a7082; --pp-amber:#ffc107; --pp-purple:#8c52ff; --pp-lime:#7ae614;
      --bg:#f7f7fb; --fg:#0f172a; --muted:#6b7280; --card:#ffffff; --ok:#10b981; --bad:#ef4444; --wait:#f59e0b;
    }
    *{box-sizing:border-box}
    body{margin:0; font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,sans-serif; color:var(--fg); background:
      radial-gradient(1200px 800px at 10% -20%, rgba(10,112,130,.15), transparent 60%),
      radial-gradient(1200px 800px at 110% 120%, rgba(140,82,255,.12), transparent 60%),
      var(--bg);}
    .wrap{max-width:900px; margin:0 auto; padding: clamp(16px,3vw,32px);}
    header{display:flex; align-items:center; gap:12px; justify-content:center; margin:8px 0 18px}
    .logo{height:48px; width:auto}
    .card{
      background:var(--card); border:1px solid rgba(0,0,0,.06); border-radius:14px;
      box-shadow: 0 10px 30px rgba(16,24,40,.06);
      padding:clamp(18px,3.2vw,28px);
    }
    .title{display:flex; align-items:center; gap:12px; margin:0 0 8px}
    .badge{display:inline-flex; align-items:center; gap:8px; font-weight:700; padding:8px 12px; border-radius:999px; font-size:.95rem}
    .badge.ok{background:rgba(16,185,129,.12); color:var(--ok)}
    .badge.bad{background:rgba(239,68,68,.12); color:var(--bad)}
    .badge.wait{background:rgba(245,158,11,.14); color:var(--wait)}
    .muted{color:var(--muted); margin:4px 0 0}
    .grid{display:grid; grid-template-columns:1fr; gap:14px; margin:18px 0 6px}
    .row{display:flex; justify-content:space-between; gap:14px; padding:12px 0; border-bottom:1px dashed rgba(0,0,0,.06)}
    .row:last-child{border-bottom:0}
    .label{color:var(--muted)}
    .val{font-weight:600}
    .warn-chip{display:inline-block; margin-top:10px; padding:6px 10px; border-radius:999px; background:#fff3cd; color:#a16207; border:1px solid #ffe08a; font-size:.9rem}
    .actions{display:flex; flex-wrap:wrap; gap:10px; margin-top:16px}
    .btn{
      appearance:none; border:0; cursor:pointer; text-decoration:none; font-weight:700;
      padding:12px 16px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;
    }
    .btn-primary{background:var(--pp-purple); color:#fff}
    .btn-primary:hover{filter:brightness(1.05)}
    .btn-ghost{background:#fff; color:var(--pp-teal); border:2px solid var(--pp-teal)}
    .btn-ghost:hover{background:rgba(10,112,130,.08)}
    .note{margin-top:14px; color:var(--muted); font-size:.95rem}
    footer{margin:20px 0; text-align:center; color:var(--muted); font-size:.9rem}
    @media (min-width:720px){
      .grid{grid-template-columns:repeat(2,1fr)}
    }
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <img src="/img/pinterpal-header.png" alt="PinterPal" class="logo">
      <img src="/img/PINTERPAL-wordmark.png" alt="PINTERPAL" class="logo" style="height:32px">
    </header>

    <div class="card">
      <div class="title">
        <?php
          $status = $ui['status'];
          $badgeClass = in_array($status, ['paid']) ? 'ok' : (in_array($status, ['open','pending','authorized']) ? 'wait' : 'bad');
          $badgeText  = in_array($status, ['paid']) ? 'Betaald' : (in_array($status, ['open','pending','authorized']) ? 'In verwerking' : ucfirst($status));
        ?>
        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeText) ?></span>
        <h1 style="margin:0"><?= htmlspecialchars($ui['title']) ?></h1>
      </div>
      <p class="muted"><?= htmlspecialchars($ui['msg']) ?></p>

      <div class="grid">
        <div class="row"><span class="label">Payment ID</span><span class="val"><?= htmlspecialchars($ui['paymentId']) ?></span></div>
        <div class="row"><span class="label">Bedrag</span><span class="val"><?= htmlspecialchars($ui['amount'].' '.$ui['currency']) ?></span></div>
        <div class="row"><span class="label">Plan</span><span class="val"><?= htmlspecialchars($ui['plan'] ?: '—') ?></span></div>
        <div class="row"><span class="label">State-check</span><span class="val"><?= $ui['stateOk'] ? 'OK' : 'Mismatch/expired' ?></span></div>
      </div>

      <?php if (!$ui['stateOk']): ?>
        <div class="warn-chip">Let op: de sessie-controle is verlopen. Refresh of log opnieuw in als iets niet klopt.</div>
      <?php endif; ?>

      <div class="actions">
        <?php if ($ui['ok']): ?>
          <a class="btn btn-primary" href="/index.php">Ga naar home</a>
          <a class="btn btn-ghost" href="/pinterpalbot.php">Open PinterPal Bot</a>
        <?php elseif (in_array($ui['status'], ['open','pending','authorized'])): ?>
          <a class="btn btn-primary" href="javascript:location.reload()">Ververs status</a>
          <a class="btn btn-ghost" href="/index.php">Terug naar home</a>
        <?php else: ?>
          <a class="btn btn-primary" href="/payment.php?plan=<?= urlencode($ui['plan'] ?: 'premium') ?>">Opnieuw proberen</a>
          <a class="btn btn-ghost" href="/index.php">Terug naar home</a>
        <?php endif; ?>
      </div>

      <p class="note">Hulp nodig? <a href="/contact.php" style="color:var(--pp-teal); font-weight:700; text-decoration:none">Contact</a>.</p>
    </div>

    <footer>© <?= date('Y') ?> PinterPal</footer>
  </div>

  <script>
    (function(){
      const status = <?= json_encode($ui['status']) ?>;
      if (['open','pending','authorized'].includes(status)) {
        const k='pp_mollie_poll';
        let n = Number(sessionStorage.getItem(k)||0);
        if (n < 12) { // max ~1 minuut
          sessionStorage.setItem(k, String(n+1));
          setTimeout(()=>location.reload(), 5000);
        }
      } else {
        sessionStorage.removeItem('pp_mollie_poll');
      }
    })();
  </script>
</body>
</html>
