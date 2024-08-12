<?php
namespace src\controllers;

use src\models\voto;
use src\config\database;

class VotoController {
    private $db;
    private $votoModel;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
        $connectionTime = $database->getConnectionTime(); 
        $this->votoModel = new Voto($this->db, $connectionTime);
    }

    public function storeVoto($data) {
        if (!isset($data['idvotante'], $data['candidatoId'], $data['region'])) {
            return ["message" => "Dados incompletos"];
        }
        $votanteJaVotou = $this->votoModel->verificarVoto($data['idvotante']);

        if ($votanteJaVotou['votou']) {
            return ["status" => false, "message" => "Você já registrou um voto."];
        }
        $result = $this->votoModel->inserirVoto($data['idvotante'], $data['candidatoId'], $data['region']);
        return $result;
        
    }

    public function getQuantidadeVotos() {
        $result = $this->votoModel->getQuantidadeVotos();
        return $result ? $result : ["message" => "Nenhum voto encontrado"];
    }

    public function getQuantidadeVotosPorRegiao() {
        $result = $this->votoModel->getQuantidadeVotosPorRegiao();
        return $result ? $result : ["message" => "Nenhum voto encontrado para este candidato"];
    }
}
?>
