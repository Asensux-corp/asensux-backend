<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

class TareaEtapa extends Pivot
{
    protected $table = 'tarea_etapa';

    protected $fillable = [
        'tarea_id',
        'etapa_id',
        'fecha_movimiento',
    ];

    public $timestamps = false;

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

}
