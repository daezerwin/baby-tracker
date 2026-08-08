<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baby_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->date('taken_at')->nullable();
            $table->boolean('is_profile')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baby_photos');
    }
};
