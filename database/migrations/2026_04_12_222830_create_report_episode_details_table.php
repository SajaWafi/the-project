<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_episode_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('medical_reports')->cascadeOnDelete();
            $table->foreignId('panic_event_id')->nullable()->constrained('panic_events')->nullOnDelete();
            $table->string('episode_title')->nullable();
            $table->date('episode_date')->nullable();
            $table->string('location_name')->nullable();
            $table->decimal('duration_min', 8, 2)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->string('severity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_episode_details');
    }
};
