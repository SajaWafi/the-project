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

        // 2. نجيبوا الطلبات بناءً على الـ doctor_id اللي في البروفايل
        $requests = DoctorRequest::with('parentProfile.user')
            ->where('doctor_id', $doctorProfile->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.request', compact('requests'));
    }

    // دالة إلغاء الطلب من قبل الدكتور
    public function cancel($id)
    {
        // تم تصحيح Auth::id() إلى Auth::user()->doctorProfile->id
        $doctorProfileId = Auth::user()->doctorProfile->id;

        $request = DoctorRequest::where('id', $id)
            ->where('doctor_id', $doctorProfileId)
            ->firstOrFail();
        
        // حذفه نهائياً
        $request->delete(); 

        return back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }
}