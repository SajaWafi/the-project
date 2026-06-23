<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentProfile;
use App\Models\Child;
use App\Models\SafeZone;
use App\Models\Location;
use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    
    public function index()
    { //هنا نجهز متغير للمناطق الآمنة.
        $safeZones = collect();
        $pathCoordinates = []; // 💡 مصفوفة جديدة لتخزين مسار الطفل
        
        $latitude = 32.8872;
        $longitude = 13.1913;
        $altitude = 0;
        $lastUpdateSec = 0;
        $isSafe = false;

        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();

        if ($parentProfile) {
            $child = Child::where('parent_id', $parentProfile->id)->first();

            if ($child) {
                // المناطق الآمنة
                $safeZones = SafeZone::where('child_id', $child->id)
                    ->where('is_active', true)
                    ->get();

                // 💡 التعديل هنا: جلب آخر موقع مسجل حتى لو كان قديم باش الدبوس والوقت يكونوا صح
                $latestLocation = Location::where('child_id', $child->id)
                    ->latest('recorded_at')
                    ->first();

                if ($latestLocation) {
                    $latitude = $latestLocation->latitude;
                    $longitude = $latestLocation->longitude;

                    $lastUpdateSec = Carbon::parse($latestLocation->recorded_at)->diffInSeconds(now());

                    // 💡 جلب مسار الطفل (آخر 100 موقع) بدون شرط اليوم، ونعكسوها باش تترتب من الأقدم للأحدث للرسم
                    $locationsHistory = Location::where('child_id', $child->id)
                        ->latest('recorded_at')
                        ->take(100) 
                        ->get()
                        ->reverse();

                    foreach ($locationsHistory as $loc) {
                        $pathCoordinates[] = [$loc->latitude, $loc->longitude];
                    }

                    // التحقق من Safe Zone
                    foreach ($safeZones as $zone) {
                        $distance = $this->calculateDistance( 
                            $latitude, $longitude,
                            $zone->center_latitude, $zone->center_longitude
                        );

                        if ($distance <= $zone->radius_meters) {
                            $isSafe = true;
                            break;
                        }
                    }
                }

                // آخر قراءة للحساسات فقط
                $latestReading = SensorReading::where('child_id', $child->id)
                    ->latest('recorded_at')
                    ->first();

                if ($latestReading) {
                    $pressure = $latestReading->pressure_level;
                    $altitude = max(0, round((1013.25 - $pressure) * 8));
                }
            }
        }

        return view('parents.location', compact(
            'latitude', 'longitude', 'altitude', 'lastUpdateSec', 
            'isSafe', 'safeZones', 'pathCoordinates' // 💡 تمرير المسار للواجهة
        ));
    }
    /*
if ($latestReading) {
    $currentPressure = $latestReading->pressure_level;
    
    // الضغط المرجعي للطابق الأرضي (سواء كان ثابت أو يتسجل ديناميكياً)
    $groundPressure = 1013.25; 

    // حساب الارتفاع بالمتر (مضروب في 8.4) وتقريب الناتج لرقم عشري واحد (مثلاً 3.4)
    $altitudeInMeters = max(0, round(($groundPressure - $currentPressure) * 8.4, 1));
}
    */
//هذه الدالة تستخدم معادلة رياضية عالمية تُسمى "معادلة هافرسين"

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; //نصف قطر الأرض
        //التحويل للراديان
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }

    public function getLiveLocation()
    {
        $parentProfile = ParentProfile::where(
            'user_id',
            Auth::id()
        )->first();

        if (!$parentProfile) {
            return response()->json([], 404);
        }

        $child = Child::where(
            'parent_id',
            $parentProfile->id
        )->first();

        if (!$child) {
            return response()->json([], 404);
        }

        $latestLocation = Location::where(
            'child_id',
            $child->id
        )
        ->latest('recorded_at')
        ->first();

        if (!$latestLocation) {
            return response()->json([], 404);
        }

        return response()->json([
            'lat' => $latestLocation->latitude,
            'lng' => $latestLocation->longitude,
            'updated_at' => $latestLocation->recorded_at
        ]);
    }
}