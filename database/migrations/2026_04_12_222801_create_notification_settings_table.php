<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('panic_enabled')->default(true);
            $table->boolean('safe_zone_exit_enabled')->default(true);
            $table->boolean('safe_zone_enter_enabled')->default(true);
            $table->boolean('heart_rate_enabled')->default(true);
            $table->boolean('appointment_enabled')->default(true);
            $table->boolean('chat_enabled')->default(true);
            $table->boolean('sound_enabled')->default(true);
            $table->boolean('vibrate_enabled')->default(true);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
