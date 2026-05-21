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
        $reading = SensorReading::create($validatedData);

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
            'message' => 'Data analyzed and alert generated if required.'
        ], 201);
    }

}