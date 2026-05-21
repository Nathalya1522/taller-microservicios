<?php
require_once __DIR__ . '/../repositories/SprintRepository.php';

class SprintController {
    private SprintRepository $repo;

    public function __construct() {
        $this->repo = new SprintRepository();
    }

    public function listar(): array {
        return array_map(fn(Sprint $s) => $s->toArray(), $this->repo->listar());
    }

    public function buscarPorId(int $id): ?array {
        return $this->repo->buscarPorId($id)?->toArray();
    }

    public function crear(array $data): array {
        foreach (['nombre', 'fecha_inicio', 'fecha_fin'] as $campo) {
            if (empty($data[$campo])) {
                return ['success' => false, 'error' => "El campo '{$campo}' es obligatorio"];
            }
        }
        if ($data['fecha_inicio'] >= $data['fecha_fin']) {
            return ['success' => false, 'error' => 'La fecha de inicio debe ser anterior a la fecha de fin'];
        }
        return ['success' => $this->repo->crear($data['nombre'], $data['fecha_inicio'], $data['fecha_fin'])];
    }

    public function eliminar(int $id): array {
        if (!$this->repo->buscarPorId($id)) {
            return ['success' => false, 'error' => 'Sprint no encontrado'];
        }
        return ['success' => $this->repo->eliminar($id)];
    }
}
