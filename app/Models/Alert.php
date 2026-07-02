<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية (Mass Assignment)
     */
    protected $fillable = [
        'child_id',
        'parent_id',
        'panic_event_id',
        'title',
        'message',
        'is_read',
        'sent_at',
        'alert_type',
        'parent_response',
    ];

    /**
     * تحويل أنواع البيانات عند استرجاعها من القاعدة (Casting)
     */
    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    // --- العلاقات (Relationships) ---

    /**
     * علاقة التنبيه بالطفل (كل تنبيه يخص طفلاً واحداً)
     */
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    /**
     * علاقة التنبيه بولي الأمر (كل تنبيه يُرسل لولي أمر واحد)
     */
    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
    
    /**
     * علاقة التنبيه بحدث ذعر (إذا كان التنبيه مرتبطاً بنوبة ذعر مسجلة)
     */
    public function panicEvent()
    {
        return $this->belongsTo(PanicEvent::class, 'panic_event_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sensorReading()
    {
        return $this->belongsTo(SensorReading::class);
    }

    public function bracelet()
    {
        return $this->belongsTo(Bracelet::class);
    }
}