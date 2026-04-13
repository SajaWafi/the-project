<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('medical_reports')->cascadeOnDelete();
            $table->text('message');
            $table->string('priority_level')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_recommendations');
    }
};
