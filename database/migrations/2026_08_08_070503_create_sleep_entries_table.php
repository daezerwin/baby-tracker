<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleep_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_id')->constrained()->cascadeOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleep_entries');
    }
};
