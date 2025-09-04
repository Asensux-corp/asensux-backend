<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->foreignId('encargado_id')->nullable()->constrained('miembros')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropForeign(['encargado_id']);
            $table->dropColumn('encargado_id');
        });
    }
};
