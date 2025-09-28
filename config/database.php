<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'happy_pets';
    private $username = 'root';     // Usuario por defecto de XAMPP
    private $password = '';         // Contraseña vacía por defecto en XAMPP
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    echo json_encode($data);
    exit;
}

function validateRequired($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            jsonResponse(['error' => "El campo '$field' es requerido"], 400);
        }
    }
}
?>