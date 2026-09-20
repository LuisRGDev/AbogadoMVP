<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('excerpt'); // Extracto para la tarjeta
            $table->longText('body'); // Contenido completo (HTML o JSON)
            $table->string('category'); // Corporativo, Contratos, Empresas, etc.
            $table->date('date'); // Fecha de publicación
            $table->string('read_time')->nullable(); // "6 min"
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
