<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pediatricians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('clinic_name')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->dateTime('next_appointment_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pediatricians');
    }
};
