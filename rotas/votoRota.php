<?php
namespace src\rotas;

use src\controllers\VotoController;
use src\config\Database;

class VotoRota {
    private $controller;
    private $rotas;

    public function __construct(Database $database) {
        $this->controller = new VotoController($database);

        $this->rotas = [
            'POST' => [
                '/index.php/voto' => 'salvaVoto'
            ],
            'GET' => [
                '/index.php/voto/quantidade' => 'buscaVotos',
                '/index.php/voto/quantidade_por_regiao' => 'buscaVotosRegiao'
            ]
        ];
    }

    public function processRoute($uri, $method) {
        $this->verificaChave();
        if (isset($this->rotas[$method][$uri])) {
            $chamaMetodo = $this->rotas[$method][$uri];
            $this->$chamaMetodo(); 
        } else {
            $this->rotaNaoEncontrada();
        }
    }

    private function salvaVoto() {
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->controller->storeVoto($data);
        echo json_encode($response);
    }

    private function buscaVotos() {
        $response = $this->controller->getQuantidadeVotos();
        echo json_encode($response);
    }

    private function buscaVotosRegiao() {
        $response = $this->controller->getQuantidadeVotosPorRegiao();
        echo json_encode($response);
    }

    private function rotaNaoEncontrada() {
        http_response_code(404);
        echo json_encode(["message" => "Rota não encontrada"]);
    }
    private function verificaChave(){
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if ($apiKey !== API_KEY) {
            http_response_code(403); 
            echo json_encode(["message" => "Acesso negado: Chave API inválida"]);
            exit();
        }
    }
}
