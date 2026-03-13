<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multimedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_multimedia_id')->constrained('secciones_multimedia');
            $table->foreignId('tipo_multimedia_id')->constrained('tipos_multimedia');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_publicacion');
            $table->string('tema', 255);
            $table->text('codigo_embed')->nullable();
            $table->string('archivo', 255)->nullable();
            $table->string('url_externa', 500)->nullable();
            $table->boolean('estado')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};