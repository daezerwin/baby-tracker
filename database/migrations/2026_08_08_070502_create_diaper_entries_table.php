<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diaper_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['wet', 'dirty', 'mixed']);
            $table->enum('consistency', ['soft', 'firm', 'runny', 'hard'])->nullable();
            $table->dateTime('occurred_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diaper_entries');
    }
};
