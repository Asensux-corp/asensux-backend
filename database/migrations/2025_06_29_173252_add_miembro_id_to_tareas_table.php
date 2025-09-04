<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->unsignedBigInteger('miembro_id')->nullable()->after('etapa_id');
            $table->foreign('miembro_id')->references('id')->on('miembros')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['miembro_id']);
            $table->dropColumn('miembro_id');
        });
    }
};
