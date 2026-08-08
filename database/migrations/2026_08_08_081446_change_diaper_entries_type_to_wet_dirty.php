<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diaper_entries', function (Blueprint $table) {
            $table->boolean('is_wet')->default(false)->after('type');
            $table->boolean('is_dirty')->default(false)->after('is_wet');
        });

        DB::table('diaper_entries')->get()->each(function ($entry) {
            DB::table('diaper_entries')->where('id', $entry->id)->update([
                'is_wet' => in_array($entry->type, ['wet', 'mixed']),
                'is_dirty' => in_array($entry->type, ['dirty', 'mixed']),
            ]);
        });

        Schema::table('diaper_entries', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('diaper_entries', function (Blueprint $table) {
            $table->enum('type', ['wet', 'dirty', 'mixed'])->nullable()->after('baby_id');
        });

        DB::table('diaper_entries')->get()->each(function ($entry) {
            $type = $entry->is_wet && $entry->is_dirty ? 'mixed' : ($entry->is_dirty ? 'dirty' : 'wet');

            DB::table('diaper_entries')->where('id', $entry->id)->update(['type' => $type]);
        });

        Schema::table('diaper_entries', function (Blueprint $table) {
            $table->dropColumn(['is_wet', 'is_dirty']);
        });
    }
};
