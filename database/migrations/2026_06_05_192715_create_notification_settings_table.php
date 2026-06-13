<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // نوع التنبيه (مثلاً: panic, safe_zone, chat, doctor_alert)
            $table->string('notification_type'); 
            
            // إعدادات التنبيه
            $table->boolean('is_enabled')->default(true); // الزر الرئيسي (أون/أوف)
            $table->boolean('has_sound')->default(true);  // الصوت
            $table->boolean('has_vibrate')->default(true); // الاهتزاز
            
            $table->timestamps();

            // سطر ذكي باش يمنع تكرار نفس النوع لنفس المستخدم مرتين
            $table->unique(['user_id', 'notification_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
