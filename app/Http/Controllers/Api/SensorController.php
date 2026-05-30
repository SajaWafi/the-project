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

            // 1. تخزين القراءة
            $reading = SensorReading::create($validatedData);

            $child = Child::find($reading->child_id);
            if (!$child) {
                return response()->json(['error' => 'Child not found'], 404);
            }

            // 2. تخزين الموقع والتحقق من المنطقة الآمنة
            $newLocation = null;
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

                    if ($child->parent_id) {
                        $this->checkSafeZone($child, $newLocation->latitude, $newLocation->longitude);
                    }
                }
            }

            // (تم حذف كود فحص النبض البسيط من هنا لمنع التكرار)

            // 3. التحليل الطبي المتقدم (هو الذي سيتولى فحص النبض والحركة وإرسال الإشعارات بذكاء)
            $this->analyzeMedicalData($reading, $child, $newLocation);

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
                
                // 1. حساب الحد الطبيعي حسب العمر
                $age = \Carbon\Carbon::parse($child->birth_date)->age;
                $maxHeartRate = ($age >= 3 && $age <= 5) ? 120 : (($age >= 6 && $age <= 12) ? 110 : 100);

                // 2. إذا تجاوز النبض الحد الطبيعي
                if ($reading->heart_rate > $maxHeartRate) {
                    $isEmergency = true;

                    // التفريق بين النبض المرتفع ونوبة الهلع:
                    // نعتبرها نوبة هلع إذا كان النبض فوق 130، أو إذا كان النبض عالي مع حركة مفرطة
                    if ($reading->heart_rate >= 130 || (!is_null($reading->motion_level) && $reading->motion_level >= 85)) {
                        $title = 'Panic Attack Detected!';
                        $message = "Critical: Child {$child->name}'s heart rate spiked to {$reading->heart_rate} bpm. Please check immediately.";
                        $alertType = 'panic';
                    } else {
                        // وإلا، فهو مجرد نبض مرتفع
                        $title = 'High Heart Rate Alert';
                        $message = "Warning: Child {$child->name} has a high heart rate ({$reading->heart_rate} bpm).";
                        $alertType = 'heart_rate';
                    }

                } elseif ($reading->heart_rate <= 50) {
                    $isEmergency = true;
                    $title = 'Abnormal Low Heart Rate';
                    $message = "Warning: Child {$child->name}'s heart rate dropped to {$reading->heart_rate} bpm.";
                    $alertType = 'panic'; // الهبوط الحاد يعتبر طوارئ قصوى
                }
            }

            // فحص الحركة المفرطة (بدون نبض عالي)
            if (!$isEmergency && !is_null($reading->motion_level) && $reading->motion_level >= 85) {
                $isEmergency = true;
                $title = 'High Physical Activity';
                $message = "Warning: Excessive motion level detected ({$reading->motion_level}) for child {$child->name}. Possible meltdown.";
                $alertType = 'activity';
            }

            // إنشاء التنبيه النهائي
            if ($isEmergency) {
                Alert::create([
                    'child_id'       => $reading->child_id,
                    'parent_id'      => $child->parent_id,       
                    'title'          => $title,
                    'message'        => $message,
                    'is_read'        => false,           
                    'sent_at'        => \Carbon\Carbon::now(),   
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