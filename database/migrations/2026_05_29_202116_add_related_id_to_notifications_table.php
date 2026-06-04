<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // إضافة العمود الجديد بعد عمود user_id، ويكون يقبل قيم فارغة nullable
            $table->unsignedBigInteger('related_id')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // حذف العمود في حالة التراجع
            $table->dropColumn('related_id');
        });
    }
};
