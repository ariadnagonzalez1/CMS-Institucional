<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('tipo', 50)->nullable();
            $table->string('path_home', 255)->nullable();
            $table->string('icono', 100)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};