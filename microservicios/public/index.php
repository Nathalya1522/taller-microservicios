<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../presentation/middlewares/CorsMiddleware.php';
require_once __DIR__ . '/../presentation/routers/Router.php';
require_once __DIR__ . '/../domain/models/Historia.php';
require_once __DIR__ . '/../domain/models/Sprint.php';
require_once __DIR__ . '/../domain/models/Reporte.php';
require_once __DIR__ . '/../presentation/repositories/HistoriaRepository.php';
require_once __DIR__ . '/../presentation/repositories/SprintRepository.php';
require_once __DIR__ . '/../presentation/repositories/ReporteRepository.php';
require_once __DIR__ . '/../application/controllers/HistoriaController.php';
require_once __DIR__ . '/../application/controllers/SprintController.php';
require_once __DIR__ . '/../application/controllers/ReporteController.php';

CorsMiddleware::aplicar();

$router = new Router();

// ── SPRINTS ──────────────────────────────────────────────────────
$router->register(new Route('GET', '/sprints', function (): void {
    echo json_encode((new SprintController())->listar());
}));

$router->register(new Route('GET', '/sprints/show', function (): void {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['error' => 'ID inválido']); return; }
    $sprint = (new SprintController())->buscarPorId($id);
    if (!$sprint) { http_response_code(404); echo json_encode(['error' => 'Sprint no encontrado']); return; }
    echo json_encode($sprint);
}));

$router->register(new Route('POST', '/sprints', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $resultado = (new SprintController())->crear($data);
    http_response_code($resultado['success'] ? 201 : 400);
    echo json_encode($resultado);
}));

$router->register(new Route('DELETE', '/sprints', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $resultado = (new SprintController())->eliminar((int)($data['id'] ?? 0));
    http_response_code($resultado['success'] ? 200 : 404);
    echo json_encode($resultado);
}));

// ── HISTORIAS ────────────────────────────────────────────────────
$router->register(new Route('GET', '/historias', function (): void {
    $ctrl = new HistoriaController();
    echo json_encode(isset($_GET['sprint_id'])
        ? $ctrl->listar((int)$_GET['sprint_id'])
        : $ctrl->listarTodas()
    );
}));

$router->register(new Route('GET', '/historias/show', function (): void {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['error' => 'ID inválido']); return; }
    $historia = (new HistoriaController())->buscarPorId($id);
    if (!$historia) { http_response_code(404); echo json_encode(['error' => 'Historia no encontrada']); return; }
    echo json_encode($historia);
}));

$router->register(new Route('POST', '/historias', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $resultado = (new HistoriaController())->crear($data);
    http_response_code($resultado['success'] ? 201 : 400);
    echo json_encode($resultado);
}));

$router->register(new Route('PUT', '/historias', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['error' => 'ID inválido']); return; }
    $resultado = (new HistoriaController())->actualizar($id, $data);
    http_response_code($resultado['success'] ? 200 : 400);
    echo json_encode($resultado);
}));

$router->register(new Route('PATCH', '/historias', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id     = (int)($data['id'] ?? 0);
    $estado = $data['estado'] ?? '';
    if ($id <= 0 || empty($estado)) { http_response_code(400); echo json_encode(['error' => 'ID o estado inválido']); return; }
    $resultado = (new HistoriaController())->actualizarEstado($id, $estado);
    http_response_code($resultado['success'] ? 200 : 400);
    echo json_encode($resultado);
}));

$router->register(new Route('DELETE', '/historias', function (): void {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $resultado = (new HistoriaController())->eliminar((int)($data['id'] ?? 0));
    http_response_code($resultado['success'] ? 200 : 404);
    echo json_encode($resultado);
}));

// ── REPORTES ─────────────────────────────────────────────────────
$router->register(new Route('GET', '/reportes', function (): void {
    $sprint_id = (int)($_GET['sprint_id'] ?? 0);
    if ($sprint_id <= 0) { http_response_code(400); echo json_encode(['error' => 'sprint_id es obligatorio']); return; }
    echo json_encode((new ReporteController())->generar($sprint_id));
}));

// ── DISPATCH (una sola vez, al final) ───────────────────────────
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);