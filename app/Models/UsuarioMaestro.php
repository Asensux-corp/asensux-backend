<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Para usar con Sanctum
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UsuarioMaestro extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // ✅ Esta línea es obligatoria
    protected $table = 'usuarios_maestros';

    protected $fillable = [
        'nombre',
        'correo',
        'empresa',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function miembros()
    {
        return $this->hasMany(Miembro::class);
    }
}
