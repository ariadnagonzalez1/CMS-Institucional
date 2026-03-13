<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bolsa_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo', 255);
            $table->string('empresa', 150)->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->string('telefono_contacto', 30)->nullable();
            $table->string('email_contacto', 150)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('requisitos')->nullable();
            $table->date('fecha_publicacion');
            $table->date('fecha_vencimiento')->nullable();
            $table->string('estado', 30)->default('activo');
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bolsa_trabajo');
    }
};