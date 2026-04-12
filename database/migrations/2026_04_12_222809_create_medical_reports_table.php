<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->string('report_type'); // weekly, monthly
            $table->unsignedTinyInteger('report_month')->nullable();
            $table->unsignedSmallInteger('report_year')->nullable();
            $table->string('title')->nullable();
            $table->string('child_status')->nullable();
            $table->integer('total_episodes')->default(0);
            $table->integer('previous_period_episodes')->default(0);
            $table->decimal('avg_episode_duration_min', 8, 2)->nullable();
            $table->decimal('longest_episode_duration_min', 8, 2)->nullable();
            $table->decimal('average_heart_rate', 8, 2)->nullable();
            $table->decimal('peak_heart_rate', 8, 2)->nullable();
            $table->decimal('min_heart_rate', 8, 2)->nullable();
            $table->decimal('average_steps', 10, 2)->nullable();
            $table->integer('safe_zone_exit_count')->default(0);
            $table->decimal('comparison_percentage', 8, 2)->nullable();
            $table->longText('summary_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_reports');
    }
};
