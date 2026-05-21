<?php

class Historia {
    private int     $id;
    private string  $titulo;
    private string  $descripcion;
    private string  $responsable;
    private string  $estado;
    private int     $puntos;
    private string  $fecha_creacion;
    private ?string $fecha_finalizacion;
    private int     $sprint_id;
    private string  $created_at;
    private string  $updated_at;

    public function __construct(
        int     $id,
        string  $titulo,
        string  $descripcion,
        string  $responsable,
        string  $estado,
        int     $puntos,
        string  $fecha_creacion,
        ?string $fecha_finalizacion,
        int     $sprint_id,
        string  $created_at,
        string  $updated_at
    ) {
        $this->id                 = $id;
        $this->titulo             = $titulo;
        $this->descripcion        = $descripcion;
        $this->responsable        = $responsable;
        $this->estado             = $estado;
        $this->puntos             = $puntos;
        $this->fecha_creacion     = $fecha_creacion;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->sprint_id          = $sprint_id;
        $this->created_at         = $created_at;
        $this->updated_at         = $updated_at;
    }

    public function getId(): int                { return $this->id; }
    public function getTitulo(): string         { return $this->titulo; }
    public function getDescripcion(): string    { return $this->descripcion; }
    public function getResponsable(): string    { return $this->responsable; }
    public function getEstado(): string         { return $this->estado; }
    public function getPuntos(): int            { return $this->puntos; }
    public function getFechaCreacion(): string  { return $this->fecha_creacion; }
    public function getFechaFinalizacion(): ?string { return $this->fecha_finalizacion; }
    public function getSprintId(): int          { return $this->sprint_id; }
    public function getCreatedAt(): string      { return $this->created_at; }
    public function getUpdatedAt(): string      { return $this->updated_at; }

    public function toArray(): array {
        return [
            'id'                 => $this->id,
            'titulo'             => $this->titulo,
            'descripcion'        => $this->descripcion,
            'responsable'        => $this->responsable,
            'estado'             => $this->estado,
            'puntos'             => $this->puntos,
            'fecha_creacion'     => $this->fecha_creacion,
            'fecha_finalizacion' => $this->fecha_finalizacion,
            'sprint_id'          => $this->sprint_id,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
