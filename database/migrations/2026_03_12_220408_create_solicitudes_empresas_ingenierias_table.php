<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_empresas_ingenierias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_empresa_id')->constrained('solicitudes_empresas')->cascadeOnDelete();
            $table->foreignId('ingenieria_id')->constrained('ingenierias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_empresas_ingenierias');
    }
};