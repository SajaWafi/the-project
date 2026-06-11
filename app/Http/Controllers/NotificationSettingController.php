<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationSetting;

class NotificationSettingController extends Controller
{
    public function toggleSetting(Request $request)
    {
        // 1. التحقق من البيانات اللي جاية من الواجهة (Security Check)
        $request->validate([
            'type' => 'required|string', // نوع التنبيه (مثلاً: panic, chat)
            // نأكدوا إن الحقل اللي بيتغير هو واحد من هادم بس باش نمنعوا أي أخطاء
            'field' => 'required|in:is_enabled,has_sound,has_vibrate', 
            'status' => 'required|boolean' // القيمة لازم تكون صح أو خطأ (1 أو 0)
        ]);

        // 2. السطر السحري (Update or Create)
        // لارافيل حيدور بالـ user_id والـ type.. لو لقاهم حيحدث الحقل المطلوب، ولو مالقاهمش حيكريت سطر جديد بروحة!
        NotificationSetting::updateOrCreate(
            [
                'user_id' => Auth::id(), 
                'notification_type' => $request->type
            ],
            [
                $request->field => $request->status
            ]
        );

        // 3. نرجعوا رد للواجهة إن العملية نجحت
        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التعديل بنجاح'
        ]);
    }
}