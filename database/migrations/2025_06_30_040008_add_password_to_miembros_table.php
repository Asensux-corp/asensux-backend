<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->string('password')->after('cargo');
            $table->rememberToken()->after('password'); // Si quieres soporte para "recordarme"
        });
    }

    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
        });
    }
};
