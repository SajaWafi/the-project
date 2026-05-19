<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    // 1. تحديد اسم الجدول في قاعدة البيانات (لأن لارافل تلقائياً حيبحث عن sensor_readings)
    protected $table = 'sensor_readings';

    // 2. حماية الحقول (Fillable) لكي يسمح لارافل بتخزين البيانات القادمة من الإسوارة دفعة واحدة
    protected $fillable = [
        'bracelet_id',
        'child_id',
        'heart_rate',
        'motion_level',
        'pressure_level',
        'place_value',
        'recorded_at'
    ];

    // 3. تحويل حقل التاريخ إلى كائن Carbon لكي نتمكن من تنسيقه بسهولة في الرسوم البيانية
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    // --- العلاقات (Relationships) ---

    /**
     * علاقة القراءة بالطفل (كل قراءة تتبع طفل واحد)
     */
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    /**
     * علاقة القراءة بالإسوارة (كل قراءة تتبع إسوارة واحدة)
     */
    public function bracelet()
    {
        return $this->belongsTo(Bracelet::class, 'bracelet_id');
    }
}