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
    Schema::create('home_tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('doctor_id')->constrained('doctor_profiles')->onDelete('cascade');
        $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
        $table->string('title'); // عنوان المهمة (مثال: تدريب التواصل البصري)
        $table->text('description')->nullable(); // تفاصيل المهمة
        $table->boolean('is_completed')->default(false); // هل الأهل أنجزوها؟
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_tasks');
    }
};
