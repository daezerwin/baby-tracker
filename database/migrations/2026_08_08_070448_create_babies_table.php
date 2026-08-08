<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('babies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('sex', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->time('time_of_birth')->nullable();
            $table->decimal('birth_weight_kg', 5, 2)->nullable();
            $table->decimal('birth_length_cm', 5, 2)->nullable();
            $table->string('blood_type')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('babies');
    }
};
