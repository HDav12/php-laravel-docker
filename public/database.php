<?php
$server   = getenv('DB_SERVER') ?: 'localhost';
$port     = (int)(getenv('DB_PORT') ?: 1433);
$dbname   = getenv('DB_NAME') ?: '';
$user     = getenv('DB_USER') ?: '';
$pass     = getenv('DB_PASSWORD') ?: '';
$encrypt  = (int)(getenv('DB_ENCRYPT') ?: 1);
$trust    = (int)(getenv('DB_TRUST_CERT') ?: 1);

$serverWithPort = $server.(($port && $port!==1433) ? ','.$port : '');

$connectionInfo = [
    'Database'      => $dbname,
    'UID'           => $user,
    'PWD'           => $pass,
    'CharacterSet'  => 'UTF-8',
    'LoginTimeout'  => 3000,
    'Encrypt'       => $encrypt ? 1 : 0,
    'TrustServerCertificate' => $trust ? 1 : 0,
];

$conn = sqlsrv_connect($serverWithPort, $connectionInfo);
if ($conn === false) {
    redirect_fail('DB_CONNECT', sqlsrv_errors(), 302);
}
