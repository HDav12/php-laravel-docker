<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;                 // ← deze miste je
use Mollie\Api\MollieApiClient;

// .env laden (stil falen als hij er niet is)
if (class_exists(Dotenv::class)) {
    Dotenv::createImmutable(__DIR__)->safeLoad();
}
//Applications/XAMPP/xamppfiles/htdocs/frontend/frontend2/pinterpal-website/php-laravel-docker/app/.env
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
