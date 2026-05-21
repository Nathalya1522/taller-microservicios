<?php

class Database {
    private static ?PDO $instance = null;

    private string $host     = 'localhost';
    private string $user     = 'root';
    private string $password = '';
    private string $name     = 'gestor_historias_db';
    private string $charset  = 'utf8mb4';

    private function __construct() {}

    public function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, $this->user, $this->password, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
                exit();
            }
        }
        return self::$instance;
    }
}