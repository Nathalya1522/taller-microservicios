<?php

require_once __DIR__ . '/../presentation/repositories/HistoriaRepository.php';

class HistoriaController {
    private HistoriaRepository $repo;

    public function __construct() {
        $this->repo = new HistoriaRepository();
    }

    public function index(): void {
        $historias = $this->repo->getAll();
        echo json_encode(['success' => true, 'data' => $historias]);
    }

    public function show(int $id): void {
        $historia = $this->repo->getById($id);
        if (!$historia) {
            http_response_code(404);
            echo json_encode(['success' => false, 'mensaje' => 'Historia no encontrada']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $historia]);
    }

    public function store(): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!isset($datos['titulo'], $datos['descripcion'], $datos['puntos'], $datos['sprint_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
            return;
        }
        $id = $this->repo->save($datos);
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $id, 'mensaje' => 'Historia creada']);
    }

    public function update(int $id): void {
        $datos = json_decode(file_get_contents('php://input'), true);
        $ok = $this->repo->update($id, $datos);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Historia actualizada' : 'Error al actualizar']);
    }

    public function destroy(int $id): void {
        $ok = $this->repo->delete($id);
        echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Historia eliminada' : 'Error al eliminar']);
    }
}