<?php
require_once "../../config/database.php";

class SprintRepository {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    
    public function listar(): array {
        $query = "SELECT * FROM sprints";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function crear(string $nombre, string $fecha_inicio, string $fecha_fin): bool {
        $query = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) 
                  VALUES (:nombre, :fecha_inicio, :fecha_fin)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            "nombre" => $nombre,
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin" => $fecha_fin
        ]);
    }

    
    public function eliminar(int $id): bool {
        $query = "DELETE FROM sprints WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(["id" => $id]);
    }
}
