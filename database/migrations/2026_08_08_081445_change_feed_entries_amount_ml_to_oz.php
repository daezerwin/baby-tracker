<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feed_entries', function (Blueprint $table) {
            $table->decimal('amount_oz', 4, 1)->nullable()->after('amount_ml');
        });

        DB::table('feed_entries')->whereNotNull('amount_ml')->get()->each(function ($entry) {
            DB::table('feed_entries')
                ->where('id', $entry->id)
                ->update(['amount_oz' => round($entry->amount_ml / 29.5735, 1)]);
        });

        Schema::table('feed_entries', function (Blueprint $table) {
            $table->dropColumn('amount_ml');
        });
    }

    public function down(): void
    {
        Schema::table('feed_entries', function (Blueprint $table) {
            $table->unsignedInteger('amount_ml')->nullable()->after('amount_oz');
        });

        DB::table('feed_entries')->whereNotNull('amount_oz')->get()->each(function ($entry) {
            DB::table('feed_entries')
                ->where('id', $entry->id)
                ->update(['amount_ml' => (int) round($entry->amount_oz * 29.5735)]);
        });

        Schema::table('feed_entries', function (Blueprint $table) {
            $table->dropColumn('amount_oz');
        });
    }
};
