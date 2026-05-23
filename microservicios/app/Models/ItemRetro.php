<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRetro extends Model
{
    protected $table = 'items_retro';

    public $timestamps = true;

    protected $fillable = [
        'retrospectiva_id',
        'tipo',
        'descripcion',
        'estado'
    ];

    // Un item pertenece a una retrospectiva
    public function retrospectiva()
    {
        return $this->belongsTo(Retrospectiva::class, 'retrospectiva_id');
    }
}