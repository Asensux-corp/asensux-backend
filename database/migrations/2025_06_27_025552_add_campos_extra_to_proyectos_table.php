<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Verifica antes de agregar para evitar duplicados si vuelves a correr la migración
            if (!Schema::hasColumn('proyectos', 'estado')) {
                $table->string('estado')->nullable()->after('cliente_id');
            }
            if (!Schema::hasColumn('proyectos', 'fecha')) {
                $table->date('fecha')->nullable()->after('estado');
            }
            if (!Schema::hasColumn('proyectos', 'repositorio')) {
                $table->string('repositorio')->nullable()->after('fecha');
            }
            if (!Schema::hasColumn('proyectos', 'url')) {
                $table->string('url')->nullable()->after('repositorio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn(['estado', 'fecha', 'repositorio', 'url']);
        });
    }
};
