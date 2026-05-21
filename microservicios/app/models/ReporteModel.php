<?php

require_once __DIR__ . '/../config/database.php';

class ReporteModel {
    private PDO $db;

    public function __construct() {
        $this->db = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array {
        $stmt = $this->db->query("SELECT *, categoria as tipo FROM retro_items ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT *, categoria as tipo FROM retro_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): int {
        $stmt = $this->db->prepare("
            INSERT INTO retro_items (categoria, descripcion, sprint_id, created_at)
            VALUES (:categoria, :descripcion, :sprint_id, NOW())
        ");
        $stmt->execute([
            ':categoria'   => $datos['tipo'],
            ':descripcion' => $datos['descripcion'],
            ':sprint_id'   => $datos['sprint_id'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->db->prepare("
            UPDATE retro_items
            SET categoria = :categoria, descripcion = :descripcion, sprint_id = :sprint_id
            WHERE id = :id
        ");
        return $stmt->execute([
            ':categoria'   => $datos['tipo'],
            ':descripcion' => $datos['descripcion'],
            ':sprint_id'   => $datos['sprint_id'],
            ':id'          => $id,
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM retro_items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
