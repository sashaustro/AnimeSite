<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('total_episodes')->nullable()->after('status');
            $table->string('duration')->nullable()->after('total_episodes');
            $table->string('broadcast_day')->nullable()->after('duration');
            $table->string('source')->nullable()->after('broadcast_day');
            $table->string('author')->nullable()->after('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn(['total_episodes', 'duration', 'broadcast_day', 'source', 'author']);
        });
    }
};
