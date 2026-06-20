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
        //هذه قيم احتياطية.
    //حتى لو ما فيهش GPS الصفحة ما تطيحش بخط
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

                // آخر موقع GPS
                $latestLocation = Location::where('child_id', $child->id)
                    ->latest('recorded_at')
                    ->first();
                //معالجة بيانات الموقع (إذا كان للطفل موقع مسجل)
                if ($latestLocation) {

                    $latitude = $latestLocation->latitude;
                    $longitude = $latestLocation->longitude;
                //إذا وجد موقعاً، يستبدل الإحداثيات الافتراضية (طرابلس) بالإحداثيات الحقيقية للطفل.

                //يحسب الفارق بالثواني بين وقت تسجيل هذا الموقع والوقت الحالي now()
                    $lastUpdateSec = Carbon::parse(
                        $latestLocation->recorded_at
                    )->diffInSeconds(now());

                    // التحقق من Safe Zone
                    foreach ($safeZones as $zone) {

                        $distance = $this->calculateDistance( //لحساب المسافة بالأمتار بين موقع الطفل الحالي ومركز المنطقة الآمنة
                            $latitude,
                            $longitude,
                            $zone->center_latitude,
                            $zone->center_longitude
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

                    $altitude = max(
                        0,
                        round((1013.25 - $pressure) * 8)
                    );
                }
            }
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
        return view('parents.location', compact(
            'latitude',
            'longitude',
            'altitude',
            'lastUpdateSec',
            'isSafe',
            'safeZones'
        ));
    }
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