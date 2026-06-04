<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Appointment;
use Carbon\Carbon;

class HomeController extends Controller
{public function home()
    {
        // --- 1. إعداد بيانات الرسم البياني (بالساعات) ---
        $todayReadings = SensorReading::whereDate('recorded_at', Carbon::today())->get();
        
        $chartLabels = [];
        $heartRatesChart = [];
        $motionLevelsChart = [];

        if ($todayReadings->isNotEmpty()) {
            $startHour = Carbon::parse($todayReadings->min('recorded_at'))->startOfHour();
            
            for ($i = 0; $i < 7; $i++) {
                $currentSlot = $startHour->copy()->addHours($i);
                $chartLabels[] = $currentSlot->format('H:00');
                
                $slotReadings = $todayReadings->filter(function($reading) use ($currentSlot) {
                    return Carbon::parse($reading->recorded_at)->format('H') == $currentSlot->format('H');
                });
                
                if ($slotReadings->count() > 0) {
                    $heartRatesChart[] = $slotReadings->avg('heart_rate');
                    $motionLevelsChart[] = $slotReadings->avg('motion_level');
                } else {
                    $heartRatesChart[] = 0;
                    $motionLevelsChart[] = 0;
                }
            }
        } else {
            $startHour = Carbon::now()->startOfHour();
            for ($i = 0; $i < 7; $i++) {
                $chartLabels[] = $startHour->copy()->addHours($i)->format('H:00');
                $heartRatesChart[] = 0;
                $motionLevelsChart[] = 0;
            }
        }

        // --- 2. إعداد المربعات (النبض، الحالة، الاتصال) ---
        $latest = SensorReading::latest('recorded_at')->first();
        $heartRate = 0;
        $activityStatus = 'No Data';
        $isConnected = false;

        if ($latest) {
            $heartRate = $latest->heart_rate;

            if ($heartRate >= 120 || $latest->motion_level >= 80) {
                $activityStatus = 'Meltdown';
            } elseif ($heartRate >= 100 || $latest->motion_level >= 50) {
                $activityStatus = 'Anxious';
            } else {
                $activityStatus = 'Calm';
            }

            $lastReadingTime = Carbon::parse($latest->recorded_at);
            if ($lastReadingTime->diffInMinutes(now()) <= 5) {
                $isConnected = true;
            }
        }

     $appointments = \App\Models\Appointment::with(['doctor.user', 'child', 'workplace'])
            ->where('parent_id', auth()->user()->parentProfile->id)
            ->whereDate('date', '>=', now()->toDateString()) // ⬅️ رجعنا هذا الشرط باش يخفي القديم
            ->orderBy('date', 'asc') // الترتيب من الأقرب للأبعد
            ->take(3)
            ->get();   // جلب أقرب موعد واحد فقط للداشبورد

       return view('parents.home', compact(
            'chartLabels', 'heartRatesChart', 'motionLevelsChart', 
            'heartRate', 'activityStatus', 'isConnected', 'appointments' // ⬅️ عدلنا هادي بس
        ));
    }
    
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
        // 1. تجهيز بيانات الرسم البياني (تبدأ من لحظة تشغيل الإسوارة اليوم)
        // ==========================================
        $todayReadings = \App\Models\SensorReading::where('child_id', $child->id)
                            ->whereDate('recorded_at', \Carbon\Carbon::today())
                            ->get();
                            
        $chartLabels = [];
        $heartRatesChart = [];
        $motionLevelsChart = [];

        if ($todayReadings->isNotEmpty()) {
            // نجيبوا أول ساعة تسجلت فيها بيانات اليوم
            $startHour = \Carbon\Carbon::parse($todayReadings->min('recorded_at'))->startOfHour();
            
            for ($i = 0; $i < 7; $i++) {
                $currentSlot = $startHour->copy()->addHours($i);
                $chartLabels[] = $currentSlot->format('H:00');
                
                // تصفية القراءات لهذي الساعة المحددة
                $slotReadings = $todayReadings->filter(function($reading) use ($currentSlot) {
                    return \Carbon\Carbon::parse($reading->recorded_at)->format('H') == $currentSlot->format('H');
                });
                
                if ($slotReadings->count() > 0) {
                    $heartRatesChart[] = round($slotReadings->avg('heart_rate'));
                    $motionLevelsChart[] = round($slotReadings->avg('motion_level'));
                } else {
                    $heartRatesChart[] = 0;
                    $motionLevelsChart[] = 0;
                }
            }
        } else {
            // لو مافيش قراءات اليوم، تبدأ الرسمة من الساعة الحالية
            $startHour = \Carbon\Carbon::now()->startOfHour();
            for ($i = 0; $i < 7; $i++) {
                $chartLabels[] = $startHour->copy()->addHours($i)->format('H:00');
                $heartRatesChart[] = 0;
                $motionLevelsChart[] = 0;
            }
        }

        // ==========================================
        // 2. الحالة اللحظية للطفل (تتحدث فوراً)
        // ==========================================
        $latest = \App\Models\SensorReading::where('child_id', $child->id)
            ->latest('recorded_at')
            ->first();
            
        $heartRate = 0;
        $liveStatus = 'Calm'; 
        $isConnected = false;

        if ($latest) {
            $heartRate = $latest->heart_rate;
            $motion = $latest->motion_level;
            
            // التقييم الطبي اللحظي (يتغير فوراً مع كل نبضة للإسوارة)
            if ($heartRate >= 120 || $motion >= 85) {
                $liveStatus = 'Panic Episode';
            } elseif ($heartRate >= 100 || $motion >= 50) {
                $liveStatus = 'Anxiety';
            } else {
                $liveStatus = 'Calm';
            }

           // التحقق من حالة الاتصال (مربوطة من الأب + آخر قراءة خلال 5 دقائق)
            $isConnected = false; // نخلوها مفصولة كافتراضي

            // لو الأب رابط الإسوارة فعلاً من التطبيق
            if ($child->is_bracelet_connected) {
                $lastReadingTime = \Carbon\Carbon::parse($latest->recorded_at);
                
                // نزيدوا نتأكدوا إنها قاعدة حية وتبعث في بيانات
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

    public function saveToken(\Illuminate\Http\Request $request)
    {
        $request->validate(['token' => 'required']);

        // يخزن التوكن أو يحدثه لو كان موجود من قبل
        \App\Models\FcmToken::updateOrCreate(
            ['fcm_token' => $request->token], // ابحث عن هذا التوكن
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