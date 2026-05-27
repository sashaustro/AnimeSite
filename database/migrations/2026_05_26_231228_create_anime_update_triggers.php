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
        DB::unprepared('
            CREATE TRIGGER trg_episodes_after_insert
            AFTER INSERT ON episodes
            FOR EACH ROW
            BEGIN
                UPDATE animes SET updated_at = NOW() WHERE id = NEW.anime_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER trg_episodes_after_update
            AFTER UPDATE ON episodes
            FOR EACH ROW
            BEGIN
                UPDATE animes SET updated_at = NOW() WHERE id = NEW.anime_id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER trg_episodes_after_delete
            AFTER DELETE ON episodes
            FOR EACH ROW
            BEGIN
                UPDATE animes SET updated_at = NOW() WHERE id = OLD.anime_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_episodes_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_episodes_after_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_episodes_after_delete');
    }
};
