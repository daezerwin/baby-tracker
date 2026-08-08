<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_guides', function (Blueprint $table) {
            $table->id();
            $table->string('age_label');
            $table->unsignedInteger('age_min_weeks');
            $table->unsignedInteger('age_max_weeks');
            $table->text('weekly_goals');
            $table->text('feeding_tips');
            $table->text('sleep_tips');
            $table->text('development_tips');
            $table->text('safety_tips');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_guides');
    }
};
