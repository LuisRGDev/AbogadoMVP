<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, string> */
    private array $orderedTables = ['practice_areas', 'attorneys', 'testimonials', 'experience_cases', 'faqs'];

    public function up(): void
    {
        foreach ($this->orderedTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->index(['is_active', 'sort_order']);
            });
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->index(['is_published', 'date']);
            $table->index('category');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        foreach ($this->orderedTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropIndex(['is_active', 'sort_order']);
            });
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'date']);
            $table->dropIndex(['category']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
