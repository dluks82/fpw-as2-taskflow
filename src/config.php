<?php

// Verificar se estamos em um ambiente local
$appEnv = getenv('APP_ENV') ?: 'production';

if ($appEnv === 'local') {
    // Carregar o autoload do Composer se existir
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';

        use Dotenv\Dotenv;

        // Carregar variáveis de ambiente do arquivo .env
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }
    } else {
        die('Erro: Arquivo vendor/autoload.php não encontrado. Execute "composer install".');
    }
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
