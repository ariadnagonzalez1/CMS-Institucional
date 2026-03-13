<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_banner_id')->constrained('secciones_banners');
            $table->foreignId('tipo_banner_id')->nullable()->constrained('tipos_banners')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo_epigrafe', 255)->nullable();
            $table->text('comentario')->nullable();
            $table->string('ruta_imagen', 255);
            $table->unsignedSmallInteger('borde_px')->nullable();
            $table->string('color_borde', 20)->nullable();
            $table->string('alineacion', 20)->nullable();
            $table->string('ajuste_ancho', 50)->nullable();
            $table->string('tipo_link', 30)->nullable();
            $table->string('url_destino', 500)->nullable();
            $table->string('tipo_ventana', 30)->nullable();
            $table->string('estado', 30)->default('activo');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};