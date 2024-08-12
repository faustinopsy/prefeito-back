<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require 'vendor/autoload.php';

use src\config\database;
use src\controllers\votoController;

define('API_KEY', 'godNotExist');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-KEY, HTTP_X_AUTHORIZATION");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dados_env = $dotenv->load();

$database = Database::getInstance($dados_env);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/index.php/voto':
        if ($method == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller = new VotoController($database);
            $response = $controller->storeVoto($data);
            echo json_encode($response);
        }
        break;

    case '/index.php/voto/quantidade':
        if ($method == 'GET') {
            $controller = new VotoController($database);
            $response = $controller->getQuantidadeVotos();
            echo json_encode($response);
        }
        break;

    case '/index.php/voto/quantidade_por_regiao':
        if ($method == 'GET') {
                $controller = new VotoController($database);
                $response = $controller->getQuantidadeVotosPorRegiao();
                echo json_encode($response);
            
        }
        break;

    default:
        echo json_encode(["message" => "Route not found"]);
        break;
}
?>
