<?php
require_once "../controllers/SprintController.php";
require_once "../controllers/HistoriaController.php";
require_once "../controllers/ReporteController.php";

class Route {
    private string $method;
    private string $path;
    private $action;

    public function __construct(string $method, string $path, callable $action) {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
    }

    public function matches(string $method, string $uri): bool {
        $cleanUri = parse_url($uri, PHP_URL_PATH);
        return $this->method === $method && $this->path === $cleanUri;
    }

    public function execute(): void {
        call_user_func($this->action);
    }
}

class Router {
    private array $routes = [];

    public function register(Route $route): void {
        $this->routes[] = $route;
    }

    public function dispatch(string $method, string $uri): void {
        header("Content-Type: application/json");

        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                $route->execute();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
}

