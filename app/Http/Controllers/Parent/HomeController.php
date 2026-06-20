<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Appointment;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    // --- 1. إعداد بيانات الرسم البياني (بالساعات) ---
    public function home()
    {
        // جلب جميع القراءات المسجلة اليوم فقط.
        $todayReadings = SensorReading::whereDate('recorded_at', Carbon::today())->get();
        
        // مصفوفات سيتم إرسالها للـ Chart.js.
        $chartLabels = [];
        $heartRatesChart = [];
        $motionLevelsChart = [];
        
        // نجعل النهاية هي الساعة الحالية، والبداية قبل 6 ساعات (المجموع 7 خانات)
        $endHour = Carbon::now()->startOfHour();
        $startHour = $endHour->copy()->subHours(6);

        // إنشاء 7 ساعات متتالية تنتهي بالساعة الحالية
        for ($i = 0; $i <= 6; $i++) {
            $currentSlot = $startHour->copy()->addHours($i);
            $chartLabels[] = $currentSlot->format('H:00');
            
            if ($todayReadings->isNotEmpty()) {
                // تصفية القراءات (الفلترة) لهذه الساعة المحددة
                $slotReadings = $todayReadings->filter(function($reading) use ($currentSlot) {
                    return Carbon::parse($reading->recorded_at)->format('H') == $currentSlot->format('H');
                });
                
                if ($slotReadings->count() > 0) {
                    $heartRatesChart[] = round($slotReadings->avg('heart_rate'));
                    $motionLevelsChart[] = $slotReadings->max('motion_level');
                } else {
                    $heartRatesChart[] = 0;
                    $motionLevelsChart[] = 0;
                }
            } else {
                // في حال لم تُسجل أي قراءة اليوم
                $heartRatesChart[] = 0;
                $motionLevelsChart[] = 0;
            }
        }

        // --- 2. إعداد المربعات (النبض، الحالة، الاتصال) ---
      // --- 2. إعداد المربعات (النبض، الحالة، الاتصال) ---
        $latest = SensorReading::latest('recorded_at')->first();
        $heartRate = 0;
        $activityStatus = 'No Data';
        $isConnected = false;

        if ($latest) {
            $heartRate = $latest->heart_rate;
            $motion = $latest->motion_level; // 👈 هادي اللي كانت ناقصة وسببت الخطأ

            // استخدمنا activityStatus لأنها هي اللي تنبعث لصفحة الـ home
            if ($heartRate >= 120 || $motion == 2) {
                $activityStatus = 'Panic Episode';
            } elseif ($heartRate >= 100 || $motion == 1) {
                $activityStatus = 'Anxiety';
            } else {
                $activityStatus = 'Calm';
            }

            $lastReadingTime = Carbon::parse($latest->recorded_at);
            if ($lastReadingTime->diffInMinutes(now()) <= 5) {
                $isConnected = true;
            }
        }

        // --- 3. جلب المواعيد القادمة الخاصة بالداشبورد ---
        $parentProfile = auth()->user()->parentProfile;
        $appointments = collect(); 

        if ($parentProfile) {
            $appointments = Appointment::with(['doctor.user', 'child', 'workplace'])
                ->where('parent_id', $parentProfile->id) 
                ->whereDate('date', '>=', Carbon::today()) 
                ->orderBy('date', 'asc') 
                ->take(3) 
                ->get();
        }

        return view('parents.home', compact(
            'chartLabels', 'heartRatesChart', 'motionLevelsChart', 
            'heartRate', 'activityStatus', 'isConnected', 'appointments'
        ));
    }

    // التحقق من اتصال المستشعر (Online/Offline) وتحديث البيانات الحية
    public function getLiveData()
    {
        $user = auth()->user();
        if (!$user || !$user->parentProfile) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $child = $user->parentProfile->children()->first();
        if (!$child) {
            return response()->json(['error' => 'No child found'], 404);
        }

        // ==========================================
        // 1. تجهيز بيانات الرسم البياني للـ 7 ساعات الأخيرة
        // ==========================================
        $todayReadings = SensorReading::where('child_id', $child->id)
                            ->whereDate('recorded_at', Carbon::today())
                            ->get();
                            
        $chartLabels = [];
        $heartRatesChart = [];
        $motionLevelsChart = [];

        $endHour = Carbon::now()->startOfHour();
        $startHour = $endHour->copy()->subHours(6);

        for ($i = 0; $i <= 6; $i++) {
            $currentSlot = $startHour->copy()->addHours($i);
            $chartLabels[] = $currentSlot->format('H:00');
            
            if ($todayReadings->isNotEmpty()) {
                $slotReadings = $todayReadings->filter(function($reading) use ($currentSlot) {
                    return Carbon::parse($reading->recorded_at)->format('H') == $currentSlot->format('H');
                });
                
                if ($slotReadings->count() > 0) {
                    $heartRatesChart[] = round($slotReadings->avg('heart_rate'));
                    $motionLevelsChart[] = $slotReadings->max('motion_level');
                } else {
                    $heartRatesChart[] = 0;
                    $motionLevelsChart[] = 0;
                }
            } else {
                $heartRatesChart[] = 0;
                $motionLevelsChart[] = 0;
            }
        }

        // ==========================================
        // 2. الحالة اللحظية للطفل (تتحدث فوراً)
        // ==========================================
        $latest = SensorReading::where('child_id', $child->id)
            ->latest('recorded_at')
            ->first();
            
        $heartRate = 0;
        $liveStatus = 'Calm'; 
        $isConnected = false;

        if ($latest) {
            $heartRate = $latest->heart_rate;
            $motion = $latest->motion_level;
            
            if ($heartRate >= 120 || $motion >= 85) {
                $liveStatus = 'Panic Episode';
            } elseif ($heartRate >= 100 || $motion >= 50) {
                $liveStatus = 'Anxiety';
            } else {
                $liveStatus = 'Calm';
            }

            if ($child->is_bracelet_connected) {
                $lastReadingTime = Carbon::parse($latest->recorded_at);
                if ($lastReadingTime->diffInMinutes(now()) <= 5) {
                    $isConnected = true;
                }
            }
        }

        // ==========================================
        // 3. إرسال البيانات للواجهة
        // ==========================================
        return response()->json([
            'chartLabels' => $chartLabels,
            'heartRatesChart' => $heartRatesChart,
            'motionLevelsChart' => $motionLevelsChart,
            'heartRate' => $heartRate,
            'live_status' => $liveStatus,
            'isConnected' => $isConnected
        ]);
    }

    // مخصصة للتعامل مع إشعارات الموبايل أو المتصفح (Push Notifications)
    public function saveToken(Request $request)
    {
        $request->validate(['token' => 'required']);

        FcmToken::updateOrCreate(
            ['fcm_token' => $request->token],
            [
                'user_id' => auth()->id(), 
                'device_type' => 'PWA-Web', 
                'is_active' => true,
                'last_used_at' => now()
            ]
        );

        return response()->json(['message' => 'Token saved successfully.']);
    }
}