<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contactos_ingenierias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingenieria_id')->constrained('ingenierias')->cascadeOnDelete();
            $table->string('nombre_contacto', 120)->nullable();
            $table->string('email', 150);
            $table->string('telefono', 30)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos_ingenierias');
    }
};