<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Models\Child;
use App\Models\Location;
use App\Models\SafeZone;
use App\Models\PanicEvent;
use Carbon\Carbon;

class SensorController extends Controller
{   //هذه نقطة الدخول API.
   /*public function receiveData(Request $request)
    {
        $validatedData = $request->validate([
            'bracelet_id'   => 'required',
            'child_id'      => 'required|exists:children,id',
            'heart_rate'    => 'nullable|integer',
            'motion_level'  => 'nullable|integer',
            'pressure_level'=> 'nullable|integer',
            'place_value'   => 'nullable|string',
        ]);

        $child = Child::find($validatedData['child_id']);

        // 🛑 بوابة التفتيش (Security Gate) 🛑
        
        // 1. لو الأب داير فصل للإسوارة من التطبيق
        if (!$child->is_bracelet_connected) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access Denied: The parent has disconnected the bracelet from the app.'
            ], 403);
        }

        // 2. لو الإسوارة اللي تبعث في البيانات رقمها ما يطابقش الرقم اللي دخله الأب
        if ((string) $child->bracelet_id !== (string) $validatedData['bracelet_id']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access Denied: Unrecognized Bracelet ID.'
            ], 403);
        }
        // ==========================================
        //إضافة وقت التسجيل
        $validatedData['recorded_at'] = Carbon::now();

        // 1. تخزين القراءة
        $reading = SensorReading::create($validatedData);

        // 2. تخزين الموقع والتحقق من المنطقة الآمنة 
        $newLocation = null;
        if (!empty($validatedData['place_value'])) {
            $coords = explode(',', $validatedData['place_value']);//اقسيم الموقع 
            
            if (count($coords) == 2) {
                $newLocation = Location::create([
                    'child_id'    => $validatedData['child_id'],
                    'bracelet_id' => $validatedData['bracelet_id'],
                    'latitude'    => (float) trim($coords[0]),
                    'longitude'   => (float) trim($coords[1]),
                    'recorded_at' => Carbon::now(),
                ]);

                if ($child->parent_id) {
                    $this->checkSafeZone($child, $newLocation->latitude, $newLocation->longitude);
                }
            }
        }

        // 3. التحليل الطبي المتقدم وإدارة النوبات
        $this->analyzeMedicalData($reading, $child);

        return response()->json([
            'status' => 'success',
            'message' => 'Data analyzed and location saved successfully.'
        ], 201);
    }
*/
public function receiveData(Request $request)
{
    $validatedData = $request->validate([
        'bracelet_id'    => 'required',
        'child_id'       => 'required|exists:children,id',
        'device_id'      => 'nullable|string', // مرسل من ESP لكن قد لا يخزن
        'heart_rate'     => 'nullable|numeric', // غيرناها لـ numeric
        'spo2'           => 'nullable|numeric',
        'temperature'    => 'nullable|numeric',
        'motion_level'   => 'nullable|integer',
        'pressure_level' => 'nullable|integer',
        'place_value'    => 'nullable|string',
        'latitude'       => 'nullable|numeric',
        'longitude'      => 'nullable|numeric',
        'gps_status'     => 'nullable|string',
        'sensor_status'  => 'nullable|string',
    ]);

    $child = Child::find($validatedData['child_id']);

    // 🛑 بوابة التفتيش (Security Gate) 🛑
    if (!$child->is_bracelet_connected) {
        return response()->json([
            'status' => 'error',
            'message' => 'Access Denied: The parent has disconnected the bracelet from the app.'
        ], 403);
    }

    if ((string) $child->bracelet_id !== (string) $validatedData['bracelet_id']) {
        return response()->json([
            'status' => 'error',
            'message' => 'Access Denied: Unrecognized Bracelet ID.'
        ], 403);
    }
    // ==========================================
    
    // إضافة وقت التسجيل
    $validatedData['recorded_at'] = \Carbon\Carbon::now();

    // 1. تخزين القراءة
    $reading = SensorReading::create($validatedData);

    // 2. تخزين الموقع والتحقق من المنطقة الآمنة 
    $newLocation = null;
    
    // التعديل هنا: نأخذ خطوط الطول والعرض مباشرة من الـ GPS
    if (!empty($validatedData['latitude']) && !empty($validatedData['longitude'])) {
        $newLocation = Location::create([
            'child_id'    => $validatedData['child_id'],
            'bracelet_id' => $validatedData['bracelet_id'],
            'latitude'    => $validatedData['latitude'],
            'longitude'   => $validatedData['longitude'],
            'recorded_at' => \Carbon\Carbon::now(),
        ]);

        if ($child->parent_id) {
            $this->checkSafeZone($child, $newLocation->latitude, $newLocation->longitude);
        }
    }

    // 3. التحليل الطبي المتقدم وإدارة النوبات
    $this->analyzeMedicalData($reading, $child);

    return response()->json([
        'status' => 'success',
        'message' => 'Data analyzed and location saved successfully.'
    ], 201);
}
/**
     * التحقق من خروج الطفل عن المنطقة الآمنة باستخدام المسافة
     */
    private function checkSafeZone($child, $currentLat, $currentLng)
    {//يجلب كل المناطق الآمنة الخاصة بالطفل.
        $safeZones = SafeZone::where('child_id', $child->id)
                             ->where('is_active', 1)
                             ->get();

        if ($safeZones->isEmpty()) return;

        $isInsideSafeZone = false;

        foreach ($safeZones as $zone) {
            $zoneLat = (float) $zone->center_latitude;
            $zoneLng = (float) $zone->center_longitude;
            $radius  = (float) $zone->radius_meters;

            $distance = $this->calculateDistance($currentLat, $currentLng, $zoneLat, $zoneLng);

            if ($distance <= $radius) {
                $isInsideSafeZone = true;
                break;
            }
        }
       // إنشاء تنبيه الخروج
        if (!$isInsideSafeZone) {
            Alert::create([
                'child_id'   => $child->id,
                'parent_id'  => $child->parent_id,
                'title'      => 'Safe Zone Breach!',
                'message'    => "Warning: {$child->name} has left the designated safe zones.",
                'is_read'    => false,
                'sent_at'    => Carbon::now(),
                'alert_type' => 'safe_zone',
            ]);
        }
    }

    /**
     * الوظيفة الطبية: التحليل وتوليد التنبيهات والنوبات (مع حساب المدة الحقيقية)
     * تم دمج الشروط وضمان عدم تداخل التنبيهات
     */
    private function analyzeMedicalData(SensorReading $reading, $child)
    {
        if (!$child->parent_id) return;
        
        $parentId = $child->parent_id;

        // 1. تحديد الحد الطبيعي للنبض بناءً على العمر
        $age = Carbon::parse($child->birth_date)->age;
        $maxHeartRate = 100; // الافتراضي لعمر 13+

        if ($age >= 3 && $age <= 5) {
            $maxHeartRate = 120;
        } elseif ($age >= 6 && $age <= 12) {
            $maxHeartRate = 110;
        }

        $hr = $reading->heart_rate ?? 0; //النبض.
        $motion = $reading->motion_level ?? 0;

        $isEmergency = false;
        $title = '';
        $message = '';
        $alertType = '';
        $eventType = '';

        // 2. تطبيق الشروط بشكل حصري (Exclusive) لتفادي التنبيهات المزدوجة
        if ($hr >= 130 || ($hr >= $maxHeartRate && $motion >= 85)) {
            // نوبة هلع: نبض عالي جداً، أو نبض عالي متزامن مع حركة مفرطة
            $isEmergency = true;
            $title = 'Panic Attack Detected!';
            $message = "Critical: Child {$child->name}'s heart rate and motion indicate a severe panic attack.";
            $alertType = 'Danger';
            $eventType = 'Panic Attack';
        } elseif ($hr >= $maxHeartRate) {
            // نبض مرتفع فقط بدون حركة مفرطة
            $isEmergency = true;
            $title = 'High Heart Rate Alert';
            $message = "Warning: {$child->name} has a high heart rate ({$hr} BPM).";
            $alertType = 'Warning';
            $eventType = 'High Heart Rate';
        } elseif ($hr > 0 && $hr <= 50) {
            // هبوط حاد في النبض
            $isEmergency = true;
            $title = 'Abnormal Low Heart Rate';
            $message = "Warning: {$child->name}'s heart rate dropped to {$hr} BPM.";
            $alertType = 'Danger';
            $eventType = 'Low Heart Rate';
        } elseif ($motion >= 85) {
            // حركة مفرطة فقط بدون ارتفاع في النبض (نوبة غضب/Meltdown)
            $isEmergency = true;
            $title = 'High Physical Activity';
            $message = "Warning: Excessive motion level detected for {$child->name}. Possible meltdown.";
            $alertType = 'Motion Alert';
            $eventType = 'Meltdown';
        }

        // =======================================================
        // 3. إدارة وقت بداية ونهاية النوبة (Panic Event)
        // =======================================================
        
        $openEvent = PanicEvent::where('child_id', $child->id)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->first();

        if ($isEmergency) { //يتأكد اولا من وجود نوبة مفتوحة او لا
            if (!$openEvent) {
                // فتح نوبة جديدة
                $panicEvent = PanicEvent::create([
                    'child_id'          => $child->id,
                    'bracelet_id'       => $reading->bracelet_id,
                    'sensor_reading_id' => $reading->id,
                    'event_type'        => $eventType,
                    'severity'          => 'High',
                    'started_at'        => Carbon::now(),
                ]);

                // إرسال التنبيه للأب
                Alert::create([
                    'child_id'       => $child->id,
                    'parent_id'      => $parentId,
                    'panic_event_id' => $panicEvent->id, 
                    'title'          => $title,
                    'message'        => $message,
                    'is_read'        => false,
                    'sent_at'        => Carbon::now(),
                    'alert_type'     => $alertType,
                ]);
            }
        } else {
            // لو الإسوارة بعتت قراءات هادية والطفل استقر
            if ($openEvent && $hr < $maxHeartRate && $motion < 85) {
                $openEvent->update([
                    'ended_at' => Carbon::now()
                ]);
            }
        }
    }

    /**
     * حساب المسافة بالأمتار (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 
        
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c; 
    }
}