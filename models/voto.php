<?php
namespace src\models;

use src\config\Database;
use PDO;

class Voto {
    private $conn;
    private $connectionTime;
    public function __construct($db, $connectionTime) {
        $this->conn = $db;
        $this->connectionTime = $connectionTime;
    }

    public function inserirVoto($idVotante, $candidatoId, $regiao) {
        try {
            $sql = "INSERT INTO votos (idvotante, candidato_Id, region) VALUES (:idvotante, :candidato_Id, :region)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':idvotante', $idVotante);
            $stmt->bindParam(':candidato_Id', $candidatoId);
            $stmt->bindParam(':region', $regiao);

            $start_time = microtime(true);
            $success = $stmt->execute();
            $end_time = microtime(true);

            $queryTime = $end_time - $start_time;

            if ($success) {
                return [
                    "success" => true,
                    "message" => "Voto inserido com sucesso.",
                    "connection_time" => $this->connectionTime,
                    "query_time" => $queryTime
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Erro ao inserir voto.",
                    "connection_time" => $this->connectionTime,
                    "query_time" => $queryTime
                ];
            }
        } catch (PDOException $e) {
            return [
                "success" => false,
                "message" => "Erro ao inserir voto: " . $e->getMessage(),
                "connection_time" => $this->connectionTime
            ];
        }
    }

    public function verificarVoto($idVotante) {
        try {
            $sql = "SELECT COUNT(*) as total FROM votos WHERE idvotante = :idvotante";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idvotante', $idVotante);

            $start_time = microtime(true);
            $stmt->execute();
            $end_time = microtime(true);

            $queryTime = $end_time - $start_time;

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                "status" => true,
                "votou" => $result['total'] > 0,
                "connection_time" => $this->connectionTime,
                "query_time" => $queryTime
            ];
        } catch (PDOException $e) {
            return [
                "status" => false,
                "message" => "Erro ao verificar voto: " . $e->getMessage(),
                "connection_time" => $this->connectionTime
            ];
        }
    }

    public function getQuantidadeVotos() {
        $query = "SELECT candidato_id, COUNT(*) as votos FROM votos GROUP BY candidato_id";
        $stmt = $this->conn->prepare($query);

        $start_time = microtime(true);
        $stmt->execute();
        $end_time = microtime(true);

        $queryTime = $end_time - $start_time;

        return [
            "status" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC),
            "connection_time" => $this->connectionTime,
            "query_time" => $queryTime
        ];
    }

    public function getQuantidadeVotosPorRegiao() {
        $query = "SELECT region, candidato_id, COUNT(*) as votos FROM votos GROUP BY region, candidato_id";
        $stmt = $this->conn->prepare($query);

        $start_time = microtime(true);
        $stmt->execute();
        $end_time = microtime(true);

        $queryTime = $end_time - $start_time;

        return [
            "status" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC),
            "connection_time" => $this->connectionTime,
            "query_time" => $queryTime
        ];
    }
}
