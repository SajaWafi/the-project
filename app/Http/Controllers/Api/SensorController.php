<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Models\Child; // استدعاء موديل الطفل لجلب بيانات الأهل
use Carbon\Carbon;

class SensorController extends Controller
{
    public function receiveData(Request $request)
    {
        $validatedData = $request->validate([
            'bracelet_id'   => 'required|exists:bracelets,id',
            'child_id'      => 'required|exists:children,id',
            'heart_rate'    => 'nullable|integer',
            'motion_level'  => 'nullable|integer',
            'pressure_level'=> 'nullable|integer',
            'place_value'   => 'nullable|string',
        ]);

        $validatedData['recorded_at'] = Carbon::now();
        $reading = SensorReading::create($validatedData);

        // تشغيل التحليل الطبي بناءً على قراءات الإسوارة الذكية
        $this->analyzeMedicalData($reading);

        return response()->json([
            'status' => 'success',
            'message' => 'Data analyzed and alert generated if required.'
        ], 201);
    }

    /**
     * الوظيفة الطبية: التحليل وتوليد التنبيهات بناءً على هيكل جدولك الحالي
     */
    private function analyzeMedicalData(SensorReading $reading)
    {
        // 1. جلب الطفل ومعرفة الـ parent_id المرتبط به
        $child = Child::find($reading->child_id);
        
        // إذا لم نجد الطفل أو لم يكن مرتبطاً بولي أمر، نتوقف لتجنب الأخطاء
        if (!$child || !$child->parent_id) {
            return;
        }

        $parentId = $child->parent_id; 
        $isEmergency = false;
        $title = '';
        $message = '';
        $alertType = '';

        // 2. تطبيق شروط النبض (Heart Rate)
        if (!is_null($reading->heart_rate)) {
            if ($reading->heart_rate >= 120) {
                $isEmergency = true;
                $title = 'Panic Attack Detected!';
                $message = "Critical: Child {$child->name}'s heart rate spiked to {$reading->heart_rate} bpm. Please check immediately.";
                $alertType = 'Danger';
            } elseif ($reading->heart_rate <= 50) {
                $isEmergency = true;
                $title = 'Abnormal Low Heart Rate';
                $message = "Warning: Child {$child->name}'s heart rate dropped to {$reading->heart_rate} bpm.";
                $alertType = 'Warning';
            }
        }

        // 3. تطبيق شروط الحركة المفرطة (Excessive Motion)
        // إذا لم تكن هناك حالة نبض خطيرة ولكن الحركة عالية جداً (نوبة غضب/Meltdown)
        if (!$isEmergency && !is_null($reading->motion_level) && $reading->motion_level >= 85) {
            $isEmergency = true;
            $title = 'High Physical Activity';
            $message = "Warning: Excessive motion level detected ({$reading->motion_level}) for child {$child->name}. Possible meltdown.";
            $alertType = 'Motion Alert';
        }

        // 4. إذا تحقق أي شرط من الشروط، نقوم بتسجيل التنبيه في جدولك الحالي
        if ($isEmergency) {
            Alert::create([
                'child_id'       => $reading->child_id,
                'parent_id'      => $parentId,       // تم جلبه ديناميكياً من علاقة الطفل
                'panic_event_id' => null,            // نضعها null مبدئياً أو نربطها لو عندك جدول الـ panic
                'title'          => $title,
                'message'        => $message,
                'is_read'        => false,           // القيمة الافتراضية للتنبيه الجديد
                'sent_at'        => Carbon::now(),   // وقت إرسال التنبيه
                'alert_type'     => $alertType,
            ]);
        }
    }
}