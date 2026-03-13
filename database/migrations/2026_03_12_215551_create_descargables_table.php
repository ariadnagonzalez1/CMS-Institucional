<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descargables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_descargable_id')->constrained('secciones_descargables');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_publicacion')->nullable();
            $table->string('tema', 255);
            $table->text('comentario')->nullable();
            $table->string('archivo', 255);
            $table->string('nombre_original_archivo', 255)->nullable();
            $table->string('tipo_archivo', 20)->nullable();
            $table->unsignedInteger('tamano_archivo_kb')->nullable();
            $table->unsignedInteger('total_descargas')->default(0);
            $table->boolean('estado')->default(true);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descargables');
    }
};