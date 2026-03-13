<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('email', 150);
            $table->string('telefono', 30)->nullable();
            $table->string('empresa', 150)->nullable();
            $table->string('asunto', 255)->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->text('mensaje');
            $table->string('estado', 30)->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_empresas');
    }
};