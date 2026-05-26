<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained('animes')->onDelete('cascade');
            $table->integer('episode_number');
            $table->string('title')->nullable(); // Наприклад: "Атака титанів: Фінал"
            $table->string('video_url'); // Посилання на відео-файл або iframe плеєра
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};