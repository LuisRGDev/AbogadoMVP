<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_areas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('num', 4); // 01, 02, etc.
            $table->string('icon')->nullable(); // building, columns, people, etc.
            $table->string('title');
            $table->string('short'); // Descripción corta para la tarjeta
            $table->text('overview'); // Descripción completa
            $table->json('matters'); // Asuntos que atiende
            $table->json('needs'); // Necesidades típicas de clientes
            $table->json('process'); // Proceso [{title, description}]
            $table->json('faqs'); // FAQs propias del área [{question, answer}]
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_areas');
    }
};
