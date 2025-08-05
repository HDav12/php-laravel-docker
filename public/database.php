<?php
$server   = getenv('DB_SERVER') . ',' . getenv('DB_PORT'); // “host,port”
$uid      = getenv('DB_UID');
$pwd      = getenv('DB_PWD');
$db       = getenv('DB_NAME');

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
