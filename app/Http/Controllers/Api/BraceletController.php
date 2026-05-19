<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    /**
     * هذه الدالة هي الباب اللي حتستقبل منه الإسوارة
     */
    public function receiveData(Request $request)
    {
        // 1. التأكد من أن البيانات اللي وصلتنا صحيحة وتطابق جدولك
        $validatedData = $request->validate([
            'bracelet_id' => 'required|exists:bracelets,id',
            'child_id' => 'required|exists:children,id',
            'heart_rate' => 'nullable|integer',
            'motion_level' => 'nullable|integer',
            'pressure_level' => 'nullable|integer',
            'place_value' => 'nullable|string',
        ]);

        // 2. إضافة وقت تسجيل القراءة تلقائياً
        $validatedData['recorded_at'] = Carbon::now();

        // 3. تخزين القراءة في قاعدة البيانات
        $reading = SensorReading::create($validatedData);

        // 4. استدعاء "الوظائف" (تحليل الحالة الصحية للطفل)
        $this->analyzeReading($reading);

        // 5. الرد على الإسوارة (عشان المبرمج يعرف أن البيانات تخزنت)
        return response()->json([
            'status' => 'success',
            'message' => 'Data recorded successfully',
            'reading_id' => $reading->id
        ], 201);
    }

    /**
     * الوظائف: دالة خاصة لتحليل البيانات واكتشاف الطوارئ
     */
    private function analyzeReading(SensorReading $reading)
    {
        $isEmergency = false;
        $emergencyTypes = [];

        // أ. وظيفة فحص النبض: لو تجاوز 120 (قد يعني نوبة هلع أو قلق شديد)
        if ($reading->heart_rate >= 120) {
            $isEmergency = true;
            $emergencyTypes[] = 'High Heart Rate';
        }

        // ب. وظيفة فحص الحركة: لو مستوى الحركة عالي جداً (قد يعني نوبة فرط حركة أو تشنج)
        // افترضت أن مقياس الحركة من 1 إلى 10
        if ($reading->motion_level >= 8) {
            $isEmergency = true;
            $emergencyTypes[] = 'Excessive Motion';
        }

        // ج. لو فيه طوارئ، نستدعي وظيفة التنبيه
        if ($isEmergency) {
            $this->triggerAlert($reading, $emergencyTypes);
        }
    }

    /**
     * الوظائف: دالة إرسال التنبيهات
     */
    private function triggerAlert(SensorReading $reading, array $emergencyTypes)
    {
        $reason = implode(' and ', $emergencyTypes);
        
        // هنا نقدروا نديروا هلبة حاجات:
        // 1. نسجلوها في جدول التنبيهات (Alerts)
        // 2. نبعثوا إشعار (Notification) لتطبيق ولي الأمر
        // 3. مبدئياً حنسجلوها في ملف الـ Log الخاص بالنظام عشان نراجعوها
        Log::alert("Emergency Alert for Child ID {$reading->child_id}: {$reason}");
    }
}