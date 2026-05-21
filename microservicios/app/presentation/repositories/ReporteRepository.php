<?php

require_once __DIR__ . '/../../models/ReporteModel.php';

class ReporteRepository {
    private ReporteModel $model;

    public function __construct() {
        $this->model = new ReporteModel();
    }

    public function getAll(): array {
        return $this->model->obtenerTodos();
    }

    public function getById(int $id): array|false {
        return $this->model->obtenerPorId($id);
    }

    public function save(array $datos): int {
        return $this->model->crear($datos);
    }

    public function update(int $id, array $datos): bool {
        return $this->model->actualizar($id, $datos);
    }

    public function delete(int $id): bool {
        return $this->model->eliminar($id);
    }
}