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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // من اللي دار الحركة؟ (نخلوها Nullable مرات يكون زائر أو سيستم)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); 
            
            // نوع الحركة (مثلاً: إضافة، تعديل، حذف، تسجيل دخول)
            $table->string('action'); 
            
            // وصف تفصيلي (مثلاً: قام الأدمن بحذف الدكتور محمد)
            $table->text('description'); 
            
            // عشان نربطوها بأي جدول (Polymorphic Relation)
            $table->nullableMorphs('subject'); 
            
            // عنوان الـ IP بتاع الجهاز اللي دار الحركة (إضافة أمنية ممتازة)
            $table->string('ip_address')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
