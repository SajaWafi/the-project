<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// مسحنا استدعاء Alert و Carbon لأننا لم نعد بحاجة إليهما هنا

class SensorReading extends Model
{
 protected $fillable = [
    'bracelet_id',
    'child_id',
    'heart_rate',
    'spo2',             // جديد
    'temperature',      // جديد
    'motion_level',
    'pressure_level',
    'latitude',         // جديد
    'longitude',        // جديد
    'place_value',
    'gps_status',       // جديد
    'sensor_status',    // جديد
    'recorded_at',
];

    // تم حذف دالة booted بالكامل لمنع التنبيهات العشوائية والمكررة

    // child relation
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}