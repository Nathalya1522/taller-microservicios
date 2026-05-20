<?php
require_once "../presentation/repositories/HistoriaRepository.php";

class HistoriaController {
    private HistoriaRepository $repo;

    public function __construct() {
        $this->repo = new HistoriaRepository();
    }

    public function listar(int $sprint_id): array {
        return $this->repo->listarPorSprint($sprint_id);
    }

    public function crear(array $data): bool {
        return $this->repo->crear(
            $data['titulo'],
            $data['descripcion'],
            $data['responsable'],
            $data['estado'],
            $data['puntos'],
            $data['fecha_creacion'],
            $data['sprint_id']
        );
    }

    public function actualizarEstado(int $id, string $estado): bool {
        return $this->repo->actualizarEstado($id, $estado);
    }

    public function eliminar(int $id): bool {
        return $this->repo->eliminar($id);
    }
}
