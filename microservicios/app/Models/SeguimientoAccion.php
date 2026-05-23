<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoAccion extends Model
{
    protected $table = 'seguimiento_acciones';

    public $timestamps = true;

    protected $fillable = [
        'accion_id',
        'retrospectiva_id',
        'cumplida',
        'observacion'
    ];

    // Pertenece a un item de tipo accion
    public function accion()
    {
        return $this->belongsTo(ItemRetro::class, 'accion_id');
    }

    // Pertenece a una retrospectiva
    public function retrospectiva()
    {
        return $this->belongsTo(Retrospectiva::class, 'retrospectiva_id');
    }
}