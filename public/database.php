<?php
// --- veilige redirect + logging ---
if (!function_exists('redirect_fail')) {
  function redirect_fail($ctx,$details=null,$http=302){
    @file_put_contents('php://stderr', "[app] FAIL[$ctx] ".json_encode($details)."\n");
    $to = '/error.php?code='.rawurlencode($ctx);
    if (!headers_sent()) { header('Location: '.$to, true, $http); exit; }
    echo '<script>location.href='.json_encode($to).'</script>'; exit;
  }
}
function envv(...$keys){ foreach($keys as $k){ $v=getenv($k); if($v!==false && $v!=='') return $v; } return ''; }
function mask($s){ $s=(string)$s; return strlen($s)<=2?$s:substr($s,0,1).str_repeat('*',max(0,strlen($s)-2)).substr($s,-1); }

// 0) Probeer eerst een ODBC-achtige DATABASE_URL
$dsn = getenv('DATABASE_URL') ?: getenv('DB_URL') ?: '';
$parts = [];
if ($dsn) {
  foreach (explode(';', $dsn) as $pair) {
    if (strpos($pair,'=')!==false){
      [$k,$v] = array_map('trim', explode('=', $pair, 2));
      $parts[strtolower($k)] = $v;
    }
  }
}

// 1) Lees waarden uit URL of uit losse ENV-varianten
$server = $parts['server']   ?? envv('DB_SERVER','DATABASE_SERVER');
$dbname = $parts['database'] ?? envv('DB_NAME','DATABASE_NAME');
$user   = $parts['uid']      ?? envv('DB_USER','DATABASE_UID','DB_USERNAME','DATABASE_USERNAME');
$pass   = $parts['pwd']      ?? envv('DB_PASSWORD','DATABASE_PASSWORD');
$port   = (int)($parts['port'] ?? envv('DB_PORT','DATABASE_PORT') ?: 1433);
$encrypt= (int)(envv('DB_ENCRYPT','DATABASE_ENCRYPT') ?: 1);
$trust  = (int)(envv('DB_TRUST_CERT','DATABASE_TRUST_CERT') ?: 1);

// 2) Niets mag missen
$missing=[];
if($server==='') $missing[]='SERVER';
if($dbname==='') $missing[]='NAME';
if($user==='')   $missing[]='USER';
if($pass==='')   $missing[]='PASS';
if($missing){ redirect_fail('CFG_DB_MISSING',['missing'=>$missing]); }

// 3) Normaliseer server voor sqlsrv_connect (host[,port])
$serverWithPort = $server;
if (stripos($serverWithPort,'tcp:')===0) $serverWithPort = substr($serverWithPort,4);
if (strpos($serverWithPort, ',') === false && $port && $port !== 1433) {
  $serverWithPort .= ','.$port;
}

// 4) Log kort wat we doen
@file_put_contents('php://stderr',
  "[db] host={$serverWithPort} db={$dbname} uid=".mask($user)." enc={$encrypt} trust={$trust}\n"
);

// 5) Connect
$connectionInfo = [
  'Database' => $dbname,
  'UID' => $user,
  'PWD' => $pass,
  'CharacterSet' => 'UTF-8',
  'LoginTimeout' => 15,
  'Encrypt' => $encrypt ? 1 : 0,
  'TrustServerCertificate' => $trust ? 1 : 0,
];

$conn = sqlsrv_connect($serverWithPort, $connectionInfo);
if ($conn === false) {
  redirect_fail('DB_CONNECT', sqlsrv_errors(), 302);
}
