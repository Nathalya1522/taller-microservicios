<?php
require_once "../../config/database.php";

class HistoriaRepository {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    
    public function listarPorSprint(int $sprint_id): array {
        $query = "SELECT * FROM historias WHERE sprint_id = :sprint_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["sprint_id" => $sprint_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function crear(
        string $titulo,
        string $descripcion,
        string $responsable,
        string $estado,
        int $puntos,
        string $fecha_creacion,
        int $sprint_id
    ): bool {
        $query = "INSERT INTO historias 
                  (titulo, descripcion, responsable, estado, puntos, fecha_creacion, sprint_id) 
                  VALUES (:titulo, :descripcion, :responsable, :estado, :puntos, :fecha_creacion, :sprint_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            "titulo" => $titulo,
            "descripcion" => $descripcion,
            "responsable" => $responsable,
            "estado" => $estado,
            "puntos" => $puntos,
            "fecha_creacion" => $fecha_creacion,
            "sprint_id" => $sprint_id
        ]);
    }

    
    public function actualizarEstado(int $id, string $estado): bool {
        $query = "UPDATE historias SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(["estado" => $estado, "id" => $id]);
    }

    
    public function eliminar(int $id): bool {
        $query = "DELETE FROM historias WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(["id" => $id]);
    }
}
