<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\SafeZone;

use App\Models\Child;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
public function index()
    {
        // 1. نجيبوا بروفايل الأب بناءً على اليوزر اللي خاش
        $parentProfile = \App\Models\ParentProfile::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        
        $safeZones = collect();
        $latitude = 32.8872; 
        $longitude = 13.1913;
        $altitude = 0;
        $lastUpdateSec = 0;
        $isSafe = false;

        if ($parentProfile) {
            // 2. نجيبوا الطفل بالرابط الصحيح
            $child = \App\Models\Child::where('parent_id', $parentProfile->id)->first();

            if ($child) {
                // جلب الـ Safe Zones لهذا الطفل
                $safeZones = \App\Models\SafeZone::where('child_id', $child->id)
                    ->where('is_active', true)
                    ->get();

                // جلب آخر قراءة
                $latestReading = \App\Models\SensorReading::where('child_id', $child->id)
                    ->latest('recorded_at')
                    ->first();

                // إذا كانت هناك قراءة وفيها إحداثيات الموقع
                if ($latestReading && $latestReading->place_value) {
                    $coords = explode(',', $latestReading->place_value);
                    if (count($coords) == 2) {
                        $latitude = (float) trim($coords[0]);
                        $longitude = (float) trim($coords[1]);
                    }

                    // حساب الارتفاع
                    $pressure = $latestReading->pressure_level;
                    $altitude = max(0, round((1013.25 - $pressure) * 8));

                    // حساب وقت آخر تحديث
                    $lastUpdateSec = \Carbon\Carbon::parse($latestReading->recorded_at)->diffInSeconds(now());

                    // التحقق من السيف زون
                    foreach ($safeZones as $zone) {
                        $distance = $this->calculateDistance($latitude, $longitude, $zone->center_latitude, $zone->center_longitude);
                        if ($distance <= $zone->radius_meters) {
                            $isSafe = true;
                            break; 
                        }
                    }
                }
            }
        }

    
    


        // داخل LocationController في دالة index
$latestLocation = \App\Models\Location::where('child_id', $child->id)
    ->latest('recorded_at')
    ->first();

$latitude = $latestLocation ? $latestLocation->latitude : 32.8872;
$longitude = $latestLocation ? $latestLocation->longitude : 13.1913;


    return view('parents.location', compact(
            'latitude',
            'longitude',
            'altitude',
            'lastUpdateSec',
            'isSafe',
            'safeZones'
        ));
    }

    /**
     * دالة مساعدة لحساب المسافة بين نقطتين بالمتر (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // بالمتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    public function getLiveLocation() {
    $latest = \App\Models\Location::latest('recorded_at')->first();
    return response()->json(['lat' => $latest->latitude, 'lng' => $latest->longitude]);
}
}
