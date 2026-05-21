<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../domain/models/Sprint.php';

class SprintRepository {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function listar(): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM sprints ORDER BY fecha_inicio DESC"
        );
        $stmt->execute();
        return array_map(fn(array $row) => $this->mapear($row), $stmt->fetchAll());
    }

    public function buscarPorId(int $id): ?Sprint {
        $stmt = $this->conn->prepare("SELECT * FROM sprints WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->mapear($row) : null;
    }

    public function crear(string $nombre, string $fecha_inicio, string $fecha_fin): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin)
             VALUES (:nombre, :fecha_inicio, :fecha_fin)"
        );
        return $stmt->execute([
            'nombre'       => $nombre,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM sprints WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function mapear(array $row): Sprint {
        return new Sprint(
            (int)$row['id'],
            $row['nombre'],
            $row['fecha_inicio'],
            $row['fecha_fin'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}