<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../domain/models/Historia.php';

class HistoriaRepository {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function listarPorSprint(int $sprint_id): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM historias WHERE sprint_id = :sprint_id ORDER BY created_at DESC"
        );
        $stmt->execute(['sprint_id' => $sprint_id]);
        return array_map(fn(array $row) => $this->mapear($row), $stmt->fetchAll());
    }

    public function listarTodas(): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM historias ORDER BY created_at DESC"
        );
        $stmt->execute();
        return array_map(fn(array $row) => $this->mapear($row), $stmt->fetchAll());
    }

    public function buscarPorId(int $id): ?Historia {
        $stmt = $this->conn->prepare("SELECT * FROM historias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->mapear($row) : null;
    }

    public function crear(
        string $titulo,
        string $descripcion,
        string $responsable,
        string $estado,
        int    $puntos,
        string $fecha_creacion,
        int    $sprint_id
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO historias
             (titulo, descripcion, responsable, estado, puntos, fecha_creacion, sprint_id)
             VALUES (:titulo, :descripcion, :responsable, :estado, :puntos, :fecha_creacion, :sprint_id)"
        );
        return $stmt->execute([
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'responsable'    => $responsable,
            'estado'         => $estado,
            'puntos'         => $puntos,
            'fecha_creacion' => $fecha_creacion,
            'sprint_id'      => $sprint_id,
        ]);
    }

    public function actualizar(
        int    $id,
        string $titulo,
        string $descripcion,
        string $responsable,
        string $estado,
        int    $puntos,
        int    $sprint_id
    ): bool {
        $fechaFin = ($estado === 'finalizada') ? date('Y-m-d') : null;
        $stmt = $this->conn->prepare(
            "UPDATE historias
             SET titulo = :titulo,
                 descripcion = :descripcion,
                 responsable = :responsable,
                 estado = :estado,
                 puntos = :puntos,
                 sprint_id = :sprint_id,
                 fecha_finalizacion = :fecha_finalizacion
             WHERE id = :id"
        );
        return $stmt->execute([
            'titulo'             => $titulo,
            'descripcion'        => $descripcion,
            'responsable'        => $responsable,
            'estado'             => $estado,
            'puntos'             => $puntos,
            'sprint_id'          => $sprint_id,
            'fecha_finalizacion' => $fechaFin,
            'id'                 => $id,
        ]);
    }

    public function actualizarEstado(int $id, string $estado): bool {
        $fechaFin = ($estado === 'finalizada') ? date('Y-m-d') : null;
        $stmt = $this->conn->prepare(
            "UPDATE historias
             SET estado = :estado, fecha_finalizacion = :fecha_finalizacion
             WHERE id = :id"
        );
        return $stmt->execute([
            'estado'             => $estado,
            'fecha_finalizacion' => $fechaFin,
            'id'                 => $id,
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM historias WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function mapear(array $row): Historia {
        return new Historia(
            (int)$row['id'],
            $row['titulo'],
            $row['descripcion'],
            $row['responsable'],
            $row['estado'],
            (int)$row['puntos'],
            $row['fecha_creacion'],
            $row['fecha_finalizacion'] ?? null,
            (int)$row['sprint_id'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}
