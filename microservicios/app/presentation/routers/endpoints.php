<?php

require_once __DIR__ . '/../../controllers/HistoriaController.php';
require_once __DIR__ . '/../../controllers/SprintController.php';
require_once __DIR__ . '/../../controllers/ReporteController.php';

class Router {
    private string $method;
    private array  $segments;

    public function __construct() {
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $uri            = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri            = trim($uri, '/');
        $this->segments = explode('/', $uri);
    }

    public function resolver(): void {
    // Buscar 'index.php' en los segmentos y tomar lo que viene después
    $recurso = '';
    $id      = null;

    $pos = array_search('index.php', $this->segments);

    if ($pos !== false) {
        $recurso = $this->segments[$pos + 1] ?? '';
        $id      = isset($this->segments[$pos + 2]) ? (int) $this->segments[$pos + 2] : null;
    }

    match ($recurso) {
        'historias' => $this->despachar(new HistoriaController(),  $id),
        'sprints'   => $this->despachar(new SprintController(),    $id),
        'reportes'  => $this->despachar(new ReporteController(),   $id),
        default     => $this->notFound(),
    };
}

    private function despachar(object $ctrl, ?int $id): void {
        match ($this->method) {
            'GET'    => $id ? $ctrl->show($id)    : $ctrl->index(),
            'POST'   => $ctrl->store(),
            'PUT'    => $id ? $ctrl->update($id)  : $this->badRequest(),
            'DELETE' => $id ? $ctrl->destroy($id) : $this->badRequest(),
            default  => $this->notFound(),
        };
    }

    private function notFound(): void {
        http_response_code(404);
        echo json_encode(['success' => false, 'mensaje' => 'Ruta no encontrada']);
    }

    private function badRequest(): void {
        http_response_code(400);
        echo json_encode(['success' => false, 'mensaje' => 'Se requiere un ID']);
    }
}