<?php

class Reporte {
    private int   $total_historias;
    private int   $finalizadas;
    private int   $activas;
    private int   $nuevas;
    private int   $impedimentos;
    private int   $puntos_totales;
    private float $velocidad;
    private array $por_responsable;

    public function __construct(
        int   $total_historias,
        int   $finalizadas,
        int   $activas,
        int   $nuevas,
        int   $impedimentos,
        int   $puntos_totales,
        float $velocidad,
        array $por_responsable = []
    ) {
        $this->total_historias  = $total_historias;
        $this->finalizadas      = $finalizadas;
        $this->activas          = $activas;
        $this->nuevas           = $nuevas;
        $this->impedimentos     = $impedimentos;
        $this->puntos_totales   = $puntos_totales;
        $this->velocidad        = $velocidad;
        $this->por_responsable  = $por_responsable;
    }

    public function getTotalHistorias(): int   { return $this->total_historias; }
    public function getFinalizadas(): int      { return $this->finalizadas; }
    public function getActivas(): int          { return $this->activas; }
    public function getNuevas(): int           { return $this->nuevas; }
    public function getImpedimentos(): int     { return $this->impedimentos; }
    public function getPuntosTotales(): int    { return $this->puntos_totales; }
    public function getVelocidad(): float      { return $this->velocidad; }
    public function getPorResponsable(): array { return $this->por_responsable; }

    public function toArray(): array {
        return [
            'total_historias' => $this->total_historias,
            'finalizadas'     => $this->finalizadas,
            'activas'         => $this->activas,
            'nuevas'          => $this->nuevas,
            'impedimentos'    => $this->impedimentos,
            'puntos_totales'  => $this->puntos_totales,
            'velocidad'       => $this->velocidad,
            'por_responsable' => $this->por_responsable,
        ];
    }
}