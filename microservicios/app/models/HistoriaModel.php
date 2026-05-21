<?php

require_once __DIR__ . '/../config/database.php';

class HistoriaModel {
    private PDO $db;

    public function __construct() {
        $this->db = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodas(): array {
        $stmt = $this->db->query("SELECT * FROM historias ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM historias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): int {
        $stmt = $this->db->prepare("
            INSERT INTO historias (titulo, descripcion, responsable, estado, puntos, sprint_id, fecha_creacion, fecha_finalizacion, created_at)
            VALUES (:titulo, :descripcion, :responsable, :estado, :puntos, :sprint_id, CURDATE(), :fecha_finalizacion, NOW())
        ");
        
        $stmt->execute([
            ':titulo'             => $datos['titulo'],
            ':descripcion'        => $datos['descripcion'],
            ':responsable'        => $datos['responsable'],
            ':estado'             => $datos['estado'] ?? 'nueva',   
            ':puntos'             => $datos['puntos'],
            ':sprint_id'          => $datos['sprint_id'],
            ':fecha_finalizacion' => $datos['fecha_fin'] ?? null,   
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->db->prepare("
            UPDATE historias
            SET titulo = :titulo, descripcion = :descripcion, responsable = :responsable,
                estado = :estado, puntos = :puntos, sprint_id = :sprint_id,
                fecha_finalizacion = :fecha_finalizacion
            WHERE id = :id
        ");
        // ↑ CORREGIDO: fecha_fin → fecha_finalizacion
        return $stmt->execute([
            ':titulo'             => $datos['titulo'],
            ':descripcion'        => $datos['descripcion'],
            ':responsable'        => $datos['responsable'],
            ':estado'             => $datos['estado'],
            ':puntos'             => $datos['puntos'],
            ':sprint_id'          => $datos['sprint_id'],
            ':fecha_finalizacion' => $datos['fecha_fin'] ?? null,
            ':id'                 => $id,
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM historias WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}