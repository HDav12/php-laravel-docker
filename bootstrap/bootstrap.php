<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Mollie\Api\MollieApiClient;

// .env in /app inladen
Dotenv::createImmutable(__DIR__ . '/../app')->safeLoad();

function base_url(): string {
  $env = $_ENV['APP_URL'] ?? getenv('APP_URL');
  if ($env) return rtrim($env, '/');
  $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) === '443');
  $scheme = $https ? 'https' : 'http';
  $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
  return "$scheme://$host";
}

function mollie(): MollieApiClient {
  $key = $_ENV['MOLLIE_KEY'] ?? getenv('MOLLIE_KEY');
  if (!$key) { http_response_code(500); exit('MOLLIE_KEY ontbreekt'); }
  $m = new MollieApiClient();
  $m->setApiKey($key);
  return $m;
}

function normalize_amount($a): string {
  return number_format((float)$a, 2, '.', '');
}
