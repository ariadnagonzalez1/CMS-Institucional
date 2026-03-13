<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_sitio', function (Blueprint $table) {
            $table->id();
            $table->string('dominio', 150)->nullable();
            $table->string('email_principal', 150)->nullable();
            $table->string('titulo_sitio', 200)->nullable();
            $table->text('descripcion_sitio')->nullable();
            $table->text('palabras_clave')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('facebook_url', 255)->nullable();
            $table->string('twitter_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->string('youtube_url', 255)->nullable();
            $table->string('google_maps_url', 500)->nullable();
            $table->string('path_directorios', 255)->nullable();
            $table->string('directorio_raiz', 255)->nullable();
            $table->string('email_envio', 150)->nullable();
            $table->unsignedInteger('limite_peso_imagen_kb')->nullable();
            $table->unsignedInteger('limite_peso_multimedia_kb')->nullable();
            $table->unsignedInteger('limite_peso_archivo_kb')->nullable();
            $table->unsignedSmallInteger('ancho_imagen_grande_px')->nullable();
            $table->unsignedSmallInteger('ancho_imagen_mediana_px')->nullable();
            $table->unsignedSmallInteger('ancho_imagen_chica_px')->nullable();
            $table->unsignedSmallInteger('ancho_imagen_minima_px')->nullable();
            $table->boolean('checked_multimedia')->default(true);
            $table->boolean('checked_archivos')->default(true);
            $table->text('caja_info_footer')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_sitio');
    }
};