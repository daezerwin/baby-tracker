<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['breast', 'bottle', 'solid']);
            $table->dateTime('fed_at');
            $table->unsignedInteger('amount_ml')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->enum('side', ['left', 'right', 'both'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_entries');
    }
};
