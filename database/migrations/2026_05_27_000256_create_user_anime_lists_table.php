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
        Schema::create('user_anime_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anime_id')->constrained('animes')->cascadeOnDelete();
            $table->string('status')->nullable(); // watching, plan_to_watch, completed, on_hold, dropped
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            // Користувач не може додати одне й те саме аніме двічі у свій список
            $table->unique(['user_id', 'anime_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_anime_lists');
    }
};
