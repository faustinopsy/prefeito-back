<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require 'vendor/autoload.php';

use src\config\database;
use src\rotas\votoRota;

define('API_KEY', 'godNotExist');

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-KEY, HTTP_X_AUTHORIZATION");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); 
    exit();
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dados_env = $dotenv->load();

$database = Database::getInstance($dados_env);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$votoRoutes = new VotoRota($database);
$votoRoutes->processRoute($uri, $method);
?>
