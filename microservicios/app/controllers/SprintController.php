<?php
require_once "../presentation/repositories/SprintRepository.php";

class SprintController {
    private SprintRepository $repo;

    public function __construct() {
        $this->repo = new SprintRepository();
    }

    public function listar(): array {
        return $this->repo->listar();
    }

    public function crear(array $data): bool {
        return $this->repo->crear($data['nombre'], $data['fecha_inicio'], $data['fecha_fin']);
    }

    public function eliminar(int $id): bool {
        return $this->repo->eliminar($id);
    }
}
