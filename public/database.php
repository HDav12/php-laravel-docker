<?php
// 1. Ophalen omgevingsvariabele 'DATABASE_URL' 
$databaseUrl = getenv('DATABASE_URL');
// Valt het buiten je server-omgeving? Dan kun je het testen door deze even hard te coderen:
$databaseUrl = "mysql://root:@localhost:3306/Users?serverVersion=mariadb-10.4.28";

if (!$databaseUrl) {
    die("Omgevingsvariabele DATABASE_URL niet gevonden.");
}

// 2. Parse de URL
$urlParts = parse_url($databaseUrl);
/**
 * Voorbeeld-URL: "mysql://root:@localhost:3306/Users?serverVersion=mariadb-10.4.28"
 * parse_url geeft je ongeveer:
 * [
 *   "scheme" => "mysql",
 *   "host"   => "localhost",
 *   "port"   => 3306,
 *   "user"   => "root",
 *   "pass"   => "",        // leeg bij geen wachtwoord
 *   "path"   => "/Users",  // let op de leading slash
 *   "query"  => "serverVersion=mariadb-10.4.28"
 * ]
 */

// Host, user, pass, port
$host = $urlParts["host"];
$user = $urlParts["user"] ?? "";
$pass = $urlParts["pass"] ?? "";
$port = $urlParts["port"] ?? 3306;

// Database naam (strip de leading slash uit path)
$dbName = isset($urlParts["path"]) ? ltrim($urlParts["path"], "/") : "";

// 3. Maak de mysqli-verbinding
$conn = new mysqli($host, $user, $pass, $dbName, $port, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

// Check op errors
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

echo "Verbinding geslaagd naar DB: $dbName (host: $host, user: $user)";
