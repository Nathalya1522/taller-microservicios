<?php
require_once "../../config/database.php";

class ReporteRepository {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    
    public function generar(int $sprint_id): array {
        $query = "SELECT 
                    COUNT(*) AS total_historias,
                    SUM(CASE WHEN estado = 'finalizada' THEN 1 ELSE 0 END) AS finalizadas,
                    SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END) AS impedimentos,
                    SUM(puntos) AS puntos_totales
                  FROM historias
                  WHERE sprint_id = :sprint_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["sprint_id" => $sprint_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $data['velocidad'] = $data['total_historias'] > 0 
            ? round(($data['finalizadas'] / $data['total_historias']) * 100, 2) 
            : 0;

        return $data;
    }
}
