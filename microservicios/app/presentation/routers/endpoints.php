<?php

class Route {
    private string $method;
    private string $path;
    private $action;

    public function __construct(string $method, string $path, callable $action) {
        $this->method = strtoupper($method);
        $this->path   = $path;
        $this->action = $action;
    }

    public function matches(string $method, string $uri): bool {
        $path = parse_url($uri, PHP_URL_PATH);

        // Extrae lo que viene después de index.php
        if (str_contains($path, 'index.php')) {
            $path = substr($path, strpos($path, 'index.php') + strlen('index.php'));
        }

        $path = '/' . ltrim($path ?: '/', '/');

        return $this->method === strtoupper($method) && $this->path === $path;
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
        header('Content-Type: application/json; charset=utf-8');

        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                $route->execute();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }
}
