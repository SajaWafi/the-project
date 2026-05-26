<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Models\Child; 
use App\Models\Location;
use App\Models\SafeZone;
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

        // جلب بيانات الطفل مباشرة باستخدام الـ id لتجنب أخطاء العلاقات
        $child = Child::find($reading->child_id);
        if (!$child) {
            return response()->json(['error' => 'Child not found'], 404);
        }

        // 2. تخزين الإحداثيات في جدول locations والتحقق من المنطقة الآمنة
        if (!empty($validatedData['place_value'])) {
            $coords = explode(',', $validatedData['place_value']);
            
            if (count($coords) == 2) {
                $newLocation = Location::create([
                    'child_id' => $validatedData['child_id'],
                    'bracelet_id' => $validatedData['bracelet_id'],
                    'latitude' => (float) trim($coords[0]),
                    'longitude' => (float) trim($coords[1]),
                    'recorded_at' => Carbon::now(),
                ]);

                // تشغيل دالة التحقق من المنطقة الآمنة
                if ($child->parent_id) {
                    $this->checkSafeZone($child, $newLocation->latitude, $newLocation->longitude);
                }
            }
        }

        // 3. التحليل الطبي للنبض بناءً على العمر
        if (!is_null($reading->heart_rate)) {
            $age = Carbon::parse($child->birth_date)->age;
            $maxHeartRate = 100; // القيمة الافتراضية

            if ($age >= 3 && $age <= 5) {
                $maxHeartRate = 120;
            } elseif ($age >= 6 && $age <= 12) {
                $maxHeartRate = 110;
            }

            if ($reading->heart_rate > $maxHeartRate) {
                Alert::create([
                    'child_id' => $child->id,
                    'parent_id' => $child->parent_id,
                    'title' => 'High Heart Rate Alert',
                    'message' => $child->name . ' has a high heart rate (' . $reading->heart_rate . ' BPM)',
                    'alert_type' => 'heart_rate',
                    'is_read' => false,
                    'sent_at' => now(),
                ]);
            }
        }

        // 4. تشغيل باقي التحليلات الطبية (الحركة والذعر)
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
    {
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

        if (!$isInsideSafeZone) {
            Alert::create([
                'child_id'       => $child->id,
                'parent_id'      => $child->parent_id,
                'title'          => 'Safe Zone Breach!',
                'message'        => "Warning: {$child->name} has left the designated safe zones.",
                'is_read'        => false,
                'sent_at'        => Carbon::now(),
                'alert_type'     => 'safe_zone',
            ]);
        }
    }

    /**
     * التحليل وتوليد التنبيهات الإضافية (بالمسميات المتوافقة مع الواجهة)
     */
    private function analyzeMedicalData(SensorReading $reading, $child)
    {
        if (!$child->parent_id) return;

        $isEmergency = false;
        $title = '';
        $message = '';
        $alertType = '';

        if (!is_null($reading->heart_rate)) {
            if ($reading->heart_rate >= 120) {
                $isEmergency = true;
                $title = 'Panic Attack Detected!';
                $message = "Critical: Child {$child->name}'s heart rate spiked to {$reading->heart_rate} bpm. Please check immediately.";
                $alertType = 'panic';
            } elseif ($reading->heart_rate <= 50) {
                $isEmergency = true;
                $title = 'Abnormal Low Heart Rate';
                $message = "Warning: Child {$child->name}'s heart rate dropped to {$reading->heart_rate} bpm.";
                $alertType = 'panic';
            }
        }

        if (!$isEmergency && !is_null($reading->motion_level) && $reading->motion_level >= 85) {
            $isEmergency = true;
            $title = 'High Physical Activity';
            $message = "Warning: Excessive motion level detected ({$reading->motion_level}) for child {$child->name}. Possible meltdown.";
            $alertType = 'activity';
        }

        if ($isEmergency) {
            Alert::create([
                'child_id'       => $reading->child_id,
                'parent_id'      => $child->parent_id,       
                'title'          => $title,
                'message'        => $message,
                'is_read'        => false,           
                'sent_at'        => Carbon::now(),   
                'alert_type'     => $alertType,
            ]);
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