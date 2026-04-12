<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor_profiles')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parent_profiles')->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedTinyInteger('from_hour');
            $table->unsignedTinyInteger('from_minute')->default(0);
            $table->string('from_period', 2);
            $table->unsignedTinyInteger('to_hour');
            $table->unsignedTinyInteger('to_minute')->default(0);
            $table->string('to_period', 2);
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
