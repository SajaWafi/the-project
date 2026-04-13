<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor_profiles')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parent_profiles')->cascadeOnDelete();
            $table->foreignId('child_id')->nullable()->constrained('children')->nullOnDelete();
            $table->timestamps();

            $table->unique(['doctor_id', 'parent_id', 'child_id'], 'conversation_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
