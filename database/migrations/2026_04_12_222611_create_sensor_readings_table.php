<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bracelet_id')->constrained('bracelets')->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->integer('heart_rate')->nullable();
            $table->integer('motion_level')->nullable();
            $table->integer('pressure_level')->nullable();
            $table->string('place_value')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};