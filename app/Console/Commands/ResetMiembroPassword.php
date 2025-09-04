<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Miembro;
use Illuminate\Support\Facades\Hash;

class ResetMiembroPassword extends Command
{
    protected $signature = 'miembro:reset-password {correo} {password=admin123}';

    protected $description = 'Reestablece la contraseña de un miembro';

    public function handle()
    {
        $correo = $this->argument('correo');
        $password = $this->argument('password');

        $miembro = Miembro::where('correo', $correo)->first();

        if (! $miembro) {
            $this->error("Miembro con correo {$correo} no encontrado.");
            return 1;
        }

        $miembro->password = Hash::make($password);
        $miembro->save();

        $this->info("✅ Contraseña actualizada exitosamente para {$correo}");
        return 0;
    }
}
