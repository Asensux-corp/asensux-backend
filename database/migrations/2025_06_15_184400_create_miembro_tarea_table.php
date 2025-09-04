<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('miembro_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('miembro_id')->constrained()->onDelete('cascade');
            $table->foreignId('tarea_id')->constrained()->onDelete('cascade');
            $table->string('rol_en_tarea', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('miembro_tarea');
    }
};
