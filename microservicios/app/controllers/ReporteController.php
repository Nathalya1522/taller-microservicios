<?php
require_once __DIR__ . '/../repositories/ReporteRepository.php';

class ReporteController {
    private ReporteRepository $repo;

    public function __construct() {
        $this->repo = new ReporteRepository();
    }

    public function generar(int $sprint_id): array {
        return $this->repo->generar($sprint_id)->toArray();
    }
}
