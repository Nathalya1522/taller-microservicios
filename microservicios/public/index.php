<?php
require_once "../presentation/routers/endpoints.php";

$router = new Router();

// Sprint endpoints
$router->register(new Route("GET", "/sprints", function() {
    $controller = new SprintController();
    echo json_encode($controller->listar());
}));

$router->register(new Route("POST", "/sprints", function() {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new SprintController();
    echo json_encode(["success" => $controller->crear($data)]);
}));

$router->register(new Route("DELETE", "/sprints", function() {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new SprintController();
    echo json_encode(["success" => $controller->eliminar($data['id'])]);
}));

// Historia endpoints
$router->register(new Route("GET", "/historias", function() {
    $sprint_id = $_GET['sprint_id'] ?? null;
    $controller = new HistoriaController();
    echo json_encode($controller->listar((int)$sprint_id));
}));

$router->register(new Route("POST", "/historias", function() {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new HistoriaController();
    echo json_encode(["success" => $controller->crear($data)]);
}));

$router->register(new Route("PUT", "/historias", function() {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new HistoriaController();
    echo json_encode(["success" => $controller->actualizarEstado($data['id'], $data['estado'])]);
}));

$router->register(new Route("DELETE", "/historias", function() {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new HistoriaController();
    echo json_encode(["success" => $controller->eliminar($data['id'])]);
}));

// Reporte endpoints
$router->register(new Route("GET", "/reportes", function() {
    $sprint_id = $_GET['sprint_id'] ?? null;
    $controller = new ReporteController();
    echo json_encode($controller->generar((int)$sprint_id));
}));

// Ejecutar router
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
