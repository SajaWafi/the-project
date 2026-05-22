<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Models\Child; 
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

        // 1. تخزين القراءات في جدول sensor_readings
        $reading = SensorReading::create($validatedData);


        // 2. تخزين الإحداثيات في جدول locations (مباشرة هنا لأن $request موجود)
        if (!empty($validatedData['place_value'])) {
            $coords = explode(',', $validatedData['place_value']);
            
            // التأكد إن الإسوارة بعتت خط الطول وخط العرض الزوز
            if (count($coords) == 2) {
                \App\Models\Location::create([
                    'child_id' => $validatedData['child_id'],
                    'bracelet_id' => $validatedData['bracelet_id'],
                    'latitude' => (float) trim($coords[0]),
                    'longitude' => (float) trim($coords[1]),
                    'recorded_at' => Carbon::now(),
                ]);
            }
        }

        // 3. تشغيل التحليل الطبي بناءً على قراءات الإسوارة الذكية

    $child = $sensorReading->child;

    $age = Carbon::parse($child->birth_date)->age;

    $maxHeartRate = 100;

    // تحديد الحد الطبيعي حسب العمر
    if ($age >= 3 && $age <= 5) {

        $maxHeartRate = 120;

    } elseif ($age >= 6 && $age <= 12) {

        $maxHeartRate = 110;

    } elseif ($age >= 13) {

        $maxHeartRate = 100;
    }

    // لو النبض أعلى من الطبيعي
    if ($sensorReading->heart_rate > $maxHeartRate) {

        Alert::create([

            'child_id' => $child->id,

            'parent_id' => $child->parent_id,

            'title' => 'High Heart Rate Alert',

            'message' =>
                $child->name .
                ' has a high heart rate (' .
                $sensorReading->heart_rate .
                ' BPM)',

            'alert_type' => 'heart_rate',

            'is_read' => false,

            'sent_at' => now(),
        ]);
    }
        // تشغيل التحليل الطبي بناءً على قراءات الإسوارة الذكية

        $this->analyzeMedicalData($reading);

        return response()->json([
            'status' => 'success',
            'message' => 'Data analyzed and location saved successfully.'
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
        if (!$isEmergency && !is_null($reading->motion_level) && $reading->motion_level >= 85) {
            $isEmergency = true;
            $title = 'High Physical Activity';
            $message = "Warning: Excessive motion level detected ({$reading->motion_level}) for child {$child->name}. Possible meltdown.";
            $alertType = 'Motion Alert';
        }

        // 4. إذا تحقق أي شرط من الشروط، نقوم بتسجيل التنبيه
        if ($isEmergency) {
            Alert::create([
                'child_id'       => $reading->child_id,
                'parent_id'      => $parentId,       
                'panic_event_id' => null,            
                'title'          => $title,
                'message'        => $message,
                'is_read'        => false,           
                'sent_at'        => Carbon::now(),   
                'alert_type'     => $alertType,
            ]);
        }
    }


}