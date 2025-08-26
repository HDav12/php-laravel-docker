<?php
// géén bootstrap/database hier — we willen geen extra errors in de errorpagina
$code = isset($_GET['code']) ? preg_replace('/[^A-Z0-9]/','', strtoupper($_GET['code'])) : 'UNKNOWN';
?><!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Er ging iets mis</title>
<style>
  body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;background:#0a7082;margin:0;color:#fff}
  .wrap{max-width:680px;margin:10vh auto;padding:2rem;background:#094e59;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.25)}
  h1{margin:0 0 .5rem}
  .muted{opacity:.85}
  .btn{display:inline-block;margin-top:1rem;padding:.8rem 1.2rem;background:#ffc107;color:#000;border-radius:8px;font-weight:700;text-decoration:none}
  code{background:rgba(255,255,255,.15);padding:.2rem .4rem;border-radius:6px}
</style>
</head>
<body>
  <div class="wrap">
    <h1>Oeps…</h1>
    <p class="muted">Er ging iets mis tijdens het verwerken van je request. Onze logs hebben een referentie opgeslagen.</p>
    <p>Referentiecode: <code><?=htmlspecialchars($code)?></code></p>
    <p class="muted">Probeer het later opnieuw. Als dit blijft gebeuren, stuur de code door naar support.</p>
    <a class="btn" href="/index.php">Terug naar home</a>
  </div>
</body>
</html>
