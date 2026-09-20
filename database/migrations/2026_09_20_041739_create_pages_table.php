<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // nosotros, contacto, privacidad, etc.
            $table->string('title');
            $table->string('eyebrow')->nullable(); // Título pequeño encima del título principal
            $table->text('text')->nullable(); // Texto descriptivo de la página
            $table->longText('content')->nullable(); // Contenido principal (HTML)
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
