<?php
class Database {
    private $servername = "localhost";
    private $username = "root"; 
    private $password = ""; 
    private $dbname = "react_db";
    private $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            // Crear conexión con PDO
            $this->pdo = new PDO("mysql:host={$this->servername};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Manejar errores de conexión
            echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
            exit();
        }
    }

    // Método para obtener la instancia PDO
    public function getPDO() {
        return $this->pdo;
    }
}
?>
