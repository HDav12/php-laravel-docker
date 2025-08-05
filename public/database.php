<?php
$host = getenv('DATABASE_SERVER');     // bijv. jouwserver.mysql.database.azure.com
$user = getenv('DATABASE_UID');        // admin@jouwserver
$pass = getenv('DATABASE_PASSWORD');
$db   = getenv('DATABASE_NAME');
$port = getenv('DATABASE_PORT') ?: 3306;

$conn = mysqli_init();
mysqli_ssl_set($conn, null, null, null, null, null);  // nodig als Azure “Enforce SSL” aan staat
mysqli_real_connect($conn, $host, $user, $pass, $db, $port)
  or die('DB-connectie faalt: ' . mysqli_connect_error());