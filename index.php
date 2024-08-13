<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require 'vendor/autoload.php';

use src\config\database;
use src\controllers\votoController;

define('API_KEY', 'godNotExist');

header("Access-Control-Allow-Credentials: true");
//header("Access-Control-Allow-Origin: https://prefeitosp.faustinopsy.com");
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-KEY, HTTP_X_AUTHORIZATION");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); 
    exit();
}


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dados_env = $dotenv->load();

$database = Database::getInstance($dados_env);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/index.php/voto':
        if ($method == 'POST') {
            $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
            if ($apiKey !== API_KEY) {
                http_response_code(403); 
                echo json_encode(["message" => "Acesso negado: Chave API inválida"]);
                exit();
            }
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
