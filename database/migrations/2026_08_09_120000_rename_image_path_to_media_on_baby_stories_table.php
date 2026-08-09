<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * An earlier release created baby_stories.image_path (photos only).
     * Video support renamed this to media_path + media_type, but editing
     * an already-run migration's content has no effect on a database that
     * already executed it — this migration performs the actual schema
     * change and backfills existing rows, and is a safe no-op on any
     * database that already has media_path (e.g. a fresh install).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('baby_stories', 'image_path') || Schema::hasColumn('baby_stories', 'media_path')) {
            return;
        }

        Schema::table('baby_stories', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('caption');
            $table->enum('media_type', ['image', 'video'])->nullable()->after('media_path');
        });

        DB::table('baby_stories')->whereNotNull('image_path')->update(['media_type' => 'image']);
        DB::statement('UPDATE baby_stories SET media_path = image_path WHERE image_path IS NOT NULL');

        Schema::table('baby_stories', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('baby_stories', 'media_path')) {
            return;
        }

        Schema::table('baby_stories', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('caption');
        });

        DB::statement("UPDATE baby_stories SET image_path = media_path WHERE media_type = 'image'");

        Schema::table('baby_stories', function (Blueprint $table) {
            $table->dropColumn(['media_path', 'media_type']);
        });
    }
};
