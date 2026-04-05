<?php
// database/migrations/2026_03_31_000001_create_estadisticas_visitas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas_visitas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->tinyInteger('hora')->unsigned();
            $table->integer('visitas')->unsigned()->default(0);
            $table->integer('visitas_unicas')->unsigned()->default(0);
            $table->timestamps();
            
            $table->index('fecha');
            $table->index('hora');
            $table->unique(['fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_visitas');
    }
};