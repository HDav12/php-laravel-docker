<?php
// **NIET** hier session_start() plaatsen!

// 0) Laad .env uit public‑map of via getenv()
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($key, $val) = explode('=', $line, 2);
        $env[trim($key)] = trim($val);
    }
}

// 1) Ophalen en “ont‑quoten” van DATABASE_URL
$databaseUrl = $env['DATABASE_URL'] 
             ?? getenv('DATABASE_URL') 
             ?? die("DATABASE_URL niet gevonden");

// verwijder ongewenste aanhalingstekens
$databaseUrl = trim($databaseUrl, "\"'");

// 2) Parse URL
$urlParts = parse_url($databaseUrl);
$host   = $urlParts['host'] ?? '127.0.0.1';
$user   = $urlParts['user'] ?? 'root';
$pass   = $urlParts['pass'] ?? '';
$port   = $urlParts['port'] ?? 3306;
$dbName = isset($urlParts['path'])
          ? ltrim($urlParts['path'], '/')
          : die("Geen database-naam in URL");

// 3) Connectie maken
$conn = new mysqli(
    '127.0.0.1',  // gebruik TCP ipv socket
    $user,
    $pass,
    $dbName,
    $port        // bv. 3306
);


// 4) Foutmelding bij mislukking
if ($conn->connect_errno) {
    die("DB‑connectie mislukt ({$conn->connect_errno}): {$conn->connect_error}");
}

