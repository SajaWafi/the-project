<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // إضافة حقل رقم الإسوارة (يقبل Null لأن في البداية مافيش إسوارة)
            $table->string('bracelet_id')->nullable()->after('id'); 
            
            // إضافة حقل حالة الاتصال (الافتراضي False يعني مفصولة)
            $table->boolean('is_bracelet_connected')->default(false)->after('bracelet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // حذف الحقول في حالة التراجع عن المايجريشن
            $table->dropColumn(['bracelet_id', 'is_bracelet_connected']);
        });
    }
};
