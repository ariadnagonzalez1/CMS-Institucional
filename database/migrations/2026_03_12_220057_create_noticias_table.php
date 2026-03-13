<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modo_texto_id')->constrained('modos_texto');
            $table->foreignId('seccion_noticia_id')->constrained('secciones_noticias');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('slug', 255)->unique();
            $table->date('fecha_publicacion');
            $table->string('volanta', 255)->nullable();
            $table->string('titulo', 255);
            $table->text('bajada')->nullable();
            $table->longText('cuerpo')->nullable();
            $table->unsignedInteger('visitas')->default(0);
            $table->unsignedInteger('comentarios_count')->default(0);
            $table->boolean('visible')->default(true);
            $table->boolean('activa')->default(true);
            $table->unsignedTinyInteger('nivel_destacado')->default(0);
            $table->boolean('es_destacado_portada')->default(false);
            $table->boolean('es_superdestacado_portada')->default(false);
            $table->boolean('permite_comentarios')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};