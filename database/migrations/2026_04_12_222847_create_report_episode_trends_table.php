<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_episode_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('medical_reports')->cascadeOnDelete();
            $table->string('point_label', 50);
            $table->integer('point_order');
            $table->integer('episode_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_episode_trends');
    }
};
