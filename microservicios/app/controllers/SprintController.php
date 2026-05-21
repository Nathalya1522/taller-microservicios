<?php

require_once __DIR__ . '/../presentation/repositories/SprintRepository.php';

class SprintController {
    private SprintRepository $repo;

    public function __construct() {
        $this->repo = new SprintRepository();
    }

    public function index(): void {
        $sprints = $this->repo->getAll();
        echo json_encode(['success' => true, 'data' => $sprints]);
    }

    public function show(int $id): void {
        $sprint = $this->repo->getById($id);
        if (!$sprint) {
            http_response_code(404);
            echo json_encode(['success' => false, 'mensaje' => 'Sprint no encontrado']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $sprint]);
    }

    public function store(): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!isset($datos['nombre'], $datos['fecha_inicio'], $datos['fecha_fin'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
            return;
        }
        $id = $this->repo->save($datos);
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $id, 'mensaje' => 'Sprint creado']);
    }

    public function update(int $id): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        $ok = $this->repo->update($id, $datos);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Sprint actualizado' : 'Error al actualizar']);
    }

    public function destroy(int $id): void {
        $ok = $this->repo->delete($id);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Sprint eliminado' : 'Error al eliminar']);
    }
}
