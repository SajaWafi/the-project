<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use App\Models\Notification; // استدعاء جدول الإشعارات
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentRequestController extends Controller
{
    // دالة فتح صفحة الطلبات والإشعارات
public function index()
    {
        // 1. جلب الطلبات (الكروت الخضراء)
       $requests = \App\Models\DoctorRequest::with('doctor.user')
            ->where('parent_id', auth()->user()->parentProfile->id)
            ->get()
            ->unique('doctor_id');

        // 2. جلب إشعارات الشات (المربعات الزرقاء)
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'chat_message')
            ->where('created_at', '>=', now()->subDays(3))
            ->latest()
            ->get()
            ->groupBy('related_id');

        // 3. جلب إشعارات المواعيد (المربع الأصفر) ⬅️ تأكدي إن الكود هذا موجود
        $appointmentNotices = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'appointment_update')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get();

        // إرسال المتغيرات للواجهة
        return view('parents.requests', compact('requests', 'notifications', 'appointmentNotices'));
    }

    // دالة قبول الطلب
    public function accept($id)
    {
        // التأكد إن الطلب مبعوث لهذا الأب بالذات
        $request = DoctorRequest::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        
        $request->update(['status' => 'accepted']);

        // هنا لاحقاً تقدر تضيف كود لإنشاء رابط في جدول doctor_parent_links

        return back()->with('success', 'تم قبول طلب الدكتور بنجاح.');
    }

    // دالة رفض الطلب
    public function reject($id)
    {
        $request = DoctorRequest::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        
        $request->update(['status' => 'rejected']);

        return back()->with('success', 'تم رفض الطلب.');
    }
}
  