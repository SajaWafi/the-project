<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    // 💡 الشرط الذكي لحل المشكلة بدون خسارة بيانات
    if (!Schema::hasTable('medical_notes')) {
        Schema::create('medical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor_profiles')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->text('note_text');
            $table->boolean('is_shared')->default(false);
            $table->timestamps();
        });
    }

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};
