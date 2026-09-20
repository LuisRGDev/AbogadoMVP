<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experience_cases', function (Blueprint $table) {
            $table->id();
            $table->string('matter'); // Nombre del asunto
            $table->string('area'); // Área de práctica
            $table->text('challenge'); // Reto del cliente
            $table->text('strategy'); // Estrategia adoptada
            $table->text('result'); // Resultado
            $table->boolean('is_demo')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_cases');
    }
};
