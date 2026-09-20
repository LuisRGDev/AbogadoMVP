<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('type', 20)->default('contact')->after('id');
            $table->date('preferred_date')->nullable()->after('message');
            $table->string('preferred_slot', 20)->nullable()->after('preferred_date');
            $table->string('meeting_mode', 20)->nullable()->after('preferred_slot');
            $table->string('source', 190)->nullable()->after('meeting_mode');
            $table->string('ip_hash', 64)->nullable()->after('source');
            $table->timestamp('read_at')->nullable()->after('notes');

            $table->index(['status', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['type']);
            $table->dropColumn(['type', 'preferred_date', 'preferred_slot', 'meeting_mode', 'source', 'ip_hash', 'read_at']);
        });
    }
};
