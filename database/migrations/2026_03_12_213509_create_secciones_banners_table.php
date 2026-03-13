<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones_banners', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->unsignedSmallInteger('ancho')->nullable();
            $table->unsignedSmallInteger('alto')->nullable();
            $table->unsignedSmallInteger('cantidad_limite')->nullable();
            $table->string('comentario', 255)->nullable();
            $table->string('imagen_ayuda', 255)->nullable();
            $table->boolean('visible_en_sitio')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones_banners');
    }
};