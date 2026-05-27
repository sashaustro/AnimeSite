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
        Schema::table('anime_genre', function (Blueprint $table) {
            $table->foreignId('anime_id')->constrained('animes')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_genre', function (Blueprint $table) {
            $table->dropForeign(['anime_id']);
            $table->dropForeign(['genre_id']);
            $table->dropColumn(['anime_id', 'genre_id']);
        });
    }
};
