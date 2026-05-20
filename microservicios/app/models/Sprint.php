<?php
class Sprint {
    private int $id;
    private string $nombre;
    private string $fecha_inicio;
    private string $fecha_fin;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $nombre,
        string $fecha_inicio,
        string $fecha_fin,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getFechaInicio(): string { return $this->fecha_inicio; }
    public function getFechaFin(): string { return $this->fecha_fin; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }
}
