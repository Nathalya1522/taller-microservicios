<?php
require_once __DIR__ . '/../repositories/HistoriaRepository.php';

class HistoriaController {
    private HistoriaRepository $repo;

    public function __construct() {
        $this->repo = new HistoriaRepository();
    }

    public function listar(int $sprint_id): array {
        return array_map(fn(Historia $h) => $h->toArray(), $this->repo->listarPorSprint($sprint_id));
    }

    public function listarTodas(): array {
        return array_map(fn(Historia $h) => $h->toArray(), $this->repo->listarTodas());
    }

    public function buscarPorId(int $id): ?array {
        return $this->repo->buscarPorId($id)?->toArray();
    }

    public function crear(array $data): array {
        foreach (['titulo','descripcion','responsable','estado','puntos','fecha_creacion','sprint_id'] as $campo) {
            if (empty($data[$campo])) {
                return ['success' => false, 'error' => "El campo '{$campo}' es obligatorio"];
            }
        }
        $ok = $this->repo->crear(
            $data['titulo'], $data['descripcion'], $data['responsable'],
            $data['estado'], (int)$data['puntos'], $data['fecha_creacion'], (int)$data['sprint_id']
        );
        return ['success' => $ok];
    }

    public function actualizar(int $id, array $data): array {
        if (!$this->repo->buscarPorId($id)) {
            return ['success' => false, 'error' => 'Historia no encontrada'];
        }
        $ok = $this->repo->actualizar(
            $id, $data['titulo'], $data['descripcion'], $data['responsable'],
            $data['estado'], (int)$data['puntos'], (int)$data['sprint_id']
        );
        return ['success' => $ok];
    }

    public function actualizarEstado(int $id, string $estado): array {
        $validos = ['nueva', 'activa', 'finalizada', 'impedimento'];
        if (!in_array($estado, $validos, true)) {
            return ['success' => false, 'error' => 'Estado no válido'];
        }
        if (!$this->repo->buscarPorId($id)) {
            return ['success' => false, 'error' => 'Historia no encontrada'];
        }
        return ['success' => $this->repo->actualizarEstado($id, $estado)];
    }

    public function eliminar(int $id): array {
        if (!$this->repo->buscarPorId($id)) {
            return ['success' => false, 'error' => 'Historia no encontrada'];
        }
        return ['success' => $this->repo->eliminar($id)];
    }
}
