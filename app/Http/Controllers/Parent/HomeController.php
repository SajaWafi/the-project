<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Appointment;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function home()
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

        // --- 3. جلب المواعيد الخاصة بأطفال هذا المستخدم فقط (المواعيد القادمة) ---
        $appointments = Appointment::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })
        ->whereDate('date', '>=', Carbon::today()) // عرض مواعيد اليوم والمستقبل بس
        ->orderBy('date', 'asc') // الترتيب من الأقرب للأبعد
        ->take(3) // باش نعرضوا أقرب 3 مواعيد بس وماتتخربش الواجهة
        ->get();

        return view('parents.home', compact(
            'chartLabels', 'heartRatesChart', 'motionLevelsChart', 
            'heartRate', 'activityStatus', 'isConnected', 'appointments'
        ));
    }

    // ضيفي هذي الدالة في نفس الكنترولر (HomeController)
    public function getLiveData()
    {
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

        // هنا نردوا البيانات على هيئة JSON للجافاسكريبت
        return response()->json([
            'chartLabels' => $chartLabels,
            'heartRatesChart' => $heartRatesChart,
            'motionLevelsChart' => $motionLevelsChart,
            'heartRate' => $heartRate,
            'activityStatus' => $activityStatus,
            'isConnected' => $isConnected
        ]);
    }
}