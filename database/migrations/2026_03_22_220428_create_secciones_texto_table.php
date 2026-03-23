<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones_texto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modo_texto_id')
                  ->constrained('modos_texto')
                  ->onUpdate('restrict')
                  ->onDelete('cascade');
            $table->string('nombre', 150);
            $table->string('color_fondo', 20)->nullable();
            $table->string('color_texto', 20)->nullable();
            $table->string('color_borde', 20)->nullable();
            $table->boolean('visible_en_sitio')->default(1);
            $table->smallInteger('orden')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones_texto');
    }
};