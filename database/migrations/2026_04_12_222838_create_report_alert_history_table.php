<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_alert_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('medical_reports')->cascadeOnDelete();
            $table->foreignId('alert_id')->nullable()->constrained('alerts')->nullOnDelete();
            $table->date('alert_date')->nullable();
            $table->string('alert_text', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_alert_history');
    }
};