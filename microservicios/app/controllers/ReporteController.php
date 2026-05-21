<?php

require_once __DIR__ . '/../presentation/repositories/ReporteRepository.php';

class ReporteController {
    private ReporteRepository $repo;

    public function __construct() {
        $this->repo = new ReporteRepository();
    }

    public function index(): void {
        $items = $this->repo->getAll();
        echo json_encode(['success' => true, 'data' => $items]);
    }

    public function show(int $id): void {
        $item = $this->repo->getById($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'mensaje' => 'Item no encontrado']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $item]);
    }

    public function store(): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!isset($datos['tipo'], $datos['descripcion'], $datos['sprint_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
            return;
        }
        $id = $this->repo->save($datos);
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $id, 'mensaje' => 'Item de retro creado']);
    }

    public function update(int $id): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        $ok = $this->repo->update($id, $datos);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Item actualizado' : 'Error al actualizar']);
    }

    public function destroy(int $id): void {
        $ok = $this->repo->delete($id);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Item eliminado' : 'Error al eliminar']);
    }
}
