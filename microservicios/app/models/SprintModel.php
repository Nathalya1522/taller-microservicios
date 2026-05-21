<?php

require_once __DIR__ . '/../config/database.php';

class SprintModel {
    private PDO $db;

    public function __construct() {
        $this->db = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array {
        $stmt = $this->db->query("SELECT * FROM sprints ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM sprints WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): int {
        $stmt = $this->db->prepare("
            INSERT INTO sprints (nombre, fecha_inicio, fecha_fin, created_at)
            VALUES (:nombre, :fecha_inicio, :fecha_fin, NOW())
        ");
        $stmt->execute([
            ':nombre'       => $datos['nombre'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin'    => $datos['fecha_fin'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->db->prepare("
            UPDATE sprints
            SET nombre = :nombre, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nombre'       => $datos['nombre'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin'    => $datos['fecha_fin'],
            ':id'           => $id,
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM sprints WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}