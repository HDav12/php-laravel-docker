<?php
$server   = getenv('DATABASE_SERVER') . ',' . getenv('DATABASE_PORT'); // “host,port”
$uid      = getenv('DATABASE_UID');
$pwd      = getenv('DATABASE_PASSWORD');
$db       = getenv('DATABASE_NAME');

$connectionOptions = [
    'Database' => $db,
    'UID'      => $uid,
    'PWD'      => $pwd,
    'Encrypt'  => 1,   // TLS verplicht bij Azure
    'TrustServerCertificate' => 0
];

$conn = sqlsrv_connect($server, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
