<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repositorio extends Model
{
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
