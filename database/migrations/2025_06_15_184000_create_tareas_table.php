<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('proyecto_id')->constrained()->onDelete('cascade');
            $table->foreignId('etapa_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('posicion')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tareas');
    }
};

