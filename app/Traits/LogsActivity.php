<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * دالة لتسجيل نشاطات النظام
     * * @param string $action نوع الحركة (مثلاً: إضافة، حذف، تعديل)
     * @param string $description وصف الحركة
     * @param object|null $subject المودل اللي صارت عليه الحركة (اختياري)
     */
    public function logActivity($action, $description, $subject = null)
    {
        ActivityLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'ip_address' => request()->ip(),
        ]);
    }
}