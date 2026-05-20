<?php
require_once "../presentation/repositories/ReporteRepository.php";

class ReporteController {
    private ReporteRepository $repo;

    public function __construct() {
        $this->repo = new ReporteRepository();
    }

    public function generar(int $sprint_id): array {
        return $this->repo->generar($sprint_id);
    }
}
