<?php
class CorsMiddleware {
    public static function aplicar(): void {
        // Permitir cualquier origen
        header("Access-Control-Allow-Origin: *");
        // Métodos HTTP permitidos
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        // Encabezados permitidos
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Manejo de preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
