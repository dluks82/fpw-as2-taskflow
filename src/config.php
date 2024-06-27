<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Carregar variáveis de ambiente do arquivo .env no ambiente de desenvolvimento
if (file_exists(__DIR__ . '/../.env')) {
  $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
  $dotenv->load();
}

// Acessar variáveis de ambiente
$servername = getenv('MYSQL_HOST') ?: 'db';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';
$database = getenv('MYSQL_DATABASE') ?: 'taskflow';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
