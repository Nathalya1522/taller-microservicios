<?php

class Middleware {

    public static function cors(): void {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    public static function json(): void {
        header("Content-Type: application/json; charset=UTF-8");
    }

    public static function aplicar(): void {
        self::cors();
        self::json();
    }
}