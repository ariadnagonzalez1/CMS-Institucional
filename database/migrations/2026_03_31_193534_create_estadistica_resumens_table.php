<?php
// database/migrations/2026_03_31_000002_create_estadisticas_resumen_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas_resumen', function (Blueprint $table) {
            $table->id();
            $table->integer('anio')->unsigned();
            $table->tinyInteger('mes')->unsigned()->nullable();
            $table->integer('total_visitas')->unsigned()->default(0);
            $table->integer('visitas_unicas')->unsigned()->default(0);
            $table->timestamps();
            
            $table->index('anio');
            $table->index('mes');
            $table->unique(['anio', 'mes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_resumen');
    }
};