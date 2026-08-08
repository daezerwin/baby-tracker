<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestone_definitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('age_min_weeks');
            $table->unsignedInteger('age_max_weeks');
            $table->enum('category', ['motor', 'cognitive', 'social', 'language']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestone_definitions');
    }
};
