<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attorneys', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('position'); // Socio(a) Director(a), Socio(a), Asociado(a) Senior
            $table->text('bio_short')->nullable(); // Biografía breve
            $table->json('bio'); // Biografía completa [párrafo1, párrafo2]
            $table->json('education'); // Formación [título — universidad]
            $table->string('credentials')->nullable(); // Cédula profesional
            $table->json('experience'); // Experiencia profesional [cargo — empresa, periodo]
            $table->json('memberships')->nullable(); // Membresías [colegio, asociación]
            $table->json('languages')->nullable();
            $table->json('areas'); // Áreas de práctica [título1, título2]
            $table->string('linkedin')->nullable();
            $table->boolean('is_demo')->default(true); // Marca si es demo
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attorneys');
    }
};
