<?php
$host = getenv('DATABASE_SERVER');
$port = getenv('DATABASE_PORT') ?: 3306;

$conn = mysqli_init();
mysqli_ssl_set($conn, null, null, null, null, null); // TLS als Azure “Enforce SSL” aan heeft
mysqli_real_connect(
    $conn,
    $host,
    getenv('DATABASE_UID'),
    getenv('DATABASE_PASSWORD'),
    getenv('DATABASE_NAME'),
    $port
) or die("DB-fout: " . mysqli_connect_error());
