<?php

namespace App\Http\Controllers\Doctor; 

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorRequestController extends Controller
{
    // دالة عرض الطلبات في واجهة الدكتور
    public function index()
    {
        // 1. نجيبوا بروفايل الدكتور الحالي
        $doctorProfile = Auth::user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        // 2. نجيبوا الطلبات بناءً على الـ doctor_id
        $requests = DoctorRequest::with('parentProfile.user')
            ->where('doctor_id', $doctorProfile->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. جلب إشعارات الشات لآخر 7 أيام وتجميعها
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'chat_message')
            ->where('created_at', '>=', now()->subDays(7)) 
            ->latest()
            ->get()
            ->groupBy('related_id');

        return view('doctor.request', compact('requests', 'notifications'));
    }

    // دالة إلغاء الطلب من قبل الدكتور
    public function cancel($id)
    {
        // 1. لازم نجيبوا بروفايل الدكتور أولاً
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        // 2. نبحثوا عن الطلب باستخدام ID البروفايل بدل الـ User ID
        $request = DoctorRequest::where('id', $id)
            ->where('doctor_id', $doctorProfile->id)
            ->firstOrFail();
        
        // حذفه نهائياً
        $request->delete(); 

        return back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }
}