<?php
namespace src\config;

use PDO;
use PDOException;
//use MongoDB\Client as MongoClient;

class Database {
    private static $instance = null;
    private $conn;
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $db_type;
    private $sqlite_path;
    private $port;
    private $oracle_sid;
    private $connectionTime;

    private function __construct($dados) {
        $this->db_type = $dados['DB_TYPE'];
        $this->host = $dados['DB_HOST'];
        $this->db_name = $dados['DB_NAME'];
        $this->username = $dados['DB_USER'];
        $this->password = $dados['DB_PASS'];
        $this->port = $dados['DB_PORT'] ?? null;
        $this->oracle_sid = $dados['DB_SID'] ?? null;

        if ($this->db_type === 'sqlite') {
            $this->sqlite_path = __DIR__ . "/my_database.db";
        }
    }

    public static function getInstance($dados) {
        if (self::$instance == null) {
            self::$instance = new Database($dados);
        }

        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            $start_time = microtime(true);
    
            try {
                switch ($this->db_type) {
                    case 'mysql':
                        $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
                        $this->conn = new PDO($dsn, $this->username, $this->password);
                        $this->conn->exec("set names utf8");
                        break;
    
                    case 'sqlite':
                        $dsn = "sqlite:" . $this->sqlite_path;
                        $this->conn = new PDO($dsn);
                        break;
    
                    case 'pgsql':
                        $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->db_name}";
                        $this->conn = new PDO($dsn, $this->username, $this->password);
                        break;
    
                    case 'oracle':
                        $dsn = "oci:dbname=//$this->host/{$this->oracle_sid}";
                        $this->conn = new PDO($dsn, $this->username, $this->password);
                        break;
    
                    case 'mssql':
                        $dsn = "sqlsrv:server={$this->host};database={$this->db_name}";
                        $this->conn = new PDO($dsn, $this->username, $this->password);
                        break;
    
                    case 'mongodb':
                        $dsn = "mongodb://{$this->host}";
                        $this->conn = new MongoClient($dsn);
                        $this->conn = $this->conn->selectDatabase($this->db_name);
                        break;
    
                    default:
                        throw new PDOException("Unsupported database type: {$this->db_type}");
                }
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            } catch (Exception $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
    
            $end_time = microtime(true);
            $this->connectionTime = $end_time - $start_time;
        }
        return $this->conn;
    }

    public function getConnectionTime() {
        return $this->connectionTime;
    }
}
