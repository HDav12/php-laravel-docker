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

// 1) Ophalen van losse variabelen
$host = $env['DATABASE_SERVER'] ?? getenv('DATABASE_SERVER') ?? '127.0.0.1';
$user = $env['DATABASE_UID'] ?? getenv('DATABASE_UID') ?? 'root';
$pass = $env['DATABASE_PASSWORD'] ?? getenv('DATABASE_PASSWORD') ?? '';
$dbName = $env['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?? '';

// 2) Connectie maken
$conn = new mysqli(
    $host,
    $user,
    $pass,
    $dbName
);

// 3) Foutmelding bij mislukking
if ($conn->connect_errno) {
    die("DB‑connectie mislukt ({$conn->connect_errno}): {$conn->connect_error}");
}

