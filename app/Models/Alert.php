<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    // إضافة هذي الميزة من الكود بتاعي
    use HasFactory;

    // اعتماد الحقول الخاصة بجدولك أنتِ لأنها الأصح
    protected $fillable = [
        'child_id',
        'parent_id',
        'panic_event_id',
        'title',
        'message',
        'is_read',
        'sent_at',
        'alert_type',
    ];

    // اعتماد التحويلات الخاصة بجدولك (خطوة احترافية جداً منكِ)
    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    // --- العلاقات (Relationships) ---

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
    
    // (اختياري) علاقة جدول حالات الذعر لو احتجتيها مستقبلاً
    public function panicEvent()
    {
        return $this->belongsTo(PanicEvent::class, 'panic_event_id');
    }
}