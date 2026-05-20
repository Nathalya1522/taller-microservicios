<?php
class Reporte {
    private int $total_historias;
    private int $finalizadas;
    private int $impedimentos;
    private int $puntos_totales;
    private float $velocidad;

    public function __construct(
        int $total_historias,
        int $finalizadas,
        int $impedimentos,
        int $puntos_totales,
        float $velocidad
    ) {
        $this->total_historias = $total_historias;
        $this->finalizadas = $finalizadas;
        $this->impedimentos = $impedimentos;
        $this->puntos_totales = $puntos_totales;
        $this->velocidad = $velocidad;
    }
    
    public function getTotalHistorias(): int { return $this->total_historias; }
    public function getFinalizadas(): int { return $this->finalizadas; }
    public function getImpedimentos(): int { return $this->impedimentos; }
    public function getPuntosTotales(): int { return $this->puntos_totales; }
    public function getVelocidad(): float { return $this->velocidad; }
}
