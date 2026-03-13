<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('noticia_id')->constrained('noticias')->cascadeOnDelete();
            $table->string('archivo', 255);
            $table->string('titulo', 255)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('es_principal')->default(false);
            $table->unsignedSmallInteger('ancho')->nullable();
            $table->unsignedSmallInteger('alto')->nullable();
            $table->boolean('recortada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias_imagenes');
    }
};