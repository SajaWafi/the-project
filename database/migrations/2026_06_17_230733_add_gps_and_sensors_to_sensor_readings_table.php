<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            // إضافة الحقول الجديدة بعد الحقول القديمة وبشكل يقبل الفراغ (nullable)
            $table->decimal('spo2', 5, 2)->nullable()->after('pressure_level');
            $table->decimal('temperature', 5, 2)->nullable()->after('spo2');
            $table->decimal('latitude', 10, 7)->nullable()->after('temperature');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('gps_status')->nullable()->after('place_value');
            $table->string('sensor_status')->nullable()->after('gps_status');
        });
    }

    public function down(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            // في حال التراجع، يتم حذف الأعمدة الجديدة فقط
            $table->dropColumn(['spo2', 'temperature', 'latitude', 'longitude', 'gps_status', 'sensor_status']);
        });
    }
};
