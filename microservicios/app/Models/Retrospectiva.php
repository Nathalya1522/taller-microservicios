<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retrospectiva extends Model
{
    protected $table = 'retrospectivas';

    public $timestamps = true;

    protected $fillable = [
        'sprint_numero',
        'sprint_nombre',
        'fecha',
        'descripcion'
    ];

    // Una retrospectiva tiene muchos items
    public function items()
    {
        return $this->hasMany(ItemRetro::class, 'retrospectiva_id');
    }
}