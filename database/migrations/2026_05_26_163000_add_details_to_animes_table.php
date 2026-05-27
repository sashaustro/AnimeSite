<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->string('studio')->nullable()->after('description');
            $table->string('voice_acting')->nullable()->after('studio');
            $table->enum('status', ['ongoing', 'completed', 'announced'])->default('ongoing')->after('voice_acting');
        });
    }

    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn(['studio', 'voice_acting', 'status']);
        });
    }
};