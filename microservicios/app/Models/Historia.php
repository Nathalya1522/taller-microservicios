<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';

    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'descripcion',
        'responsable',
        'estado',
        'puntos',
        'fecha_creacion',
        'fecha_finalizacion',
        'sprint_id'
    ];

    // Una historia pertenece a un sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }
}