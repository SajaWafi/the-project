<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // الموديل الجاهز عندكِ في قاعدة البيانات
use Illuminate\Support\Facades\Auth;

class ParentComplaintController extends Controller
{
    // 1. عرض واجهة الشكاوى للأهل (مباشرة وبدون جلب الدكاترة من الداتابيز لتجنب أي تعارض)
    public function create()
    {
        return view('complaint');
    }

    // 2. حفظ الشكوى في جدول complaints
   public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string|max:5000',
            'doctor_info' => 'nullable|string|max:255', 
        ]);

        // دمج نوع الشكوى بشكل افتراضي في بداية أي رسالة
        $finalMessage = "Category: [" . $request->category . "]\n\nDetails:\n" . $request->message;

        // تخصيص الرسالة إذا كانت الشكوى ضد طبيب
        if ($request->category == 'Doctor Dispute' && $request->doctor_info) {
            $finalMessage = "Notice: Complaint against Doctor (" . $request->doctor_info . ")\n\nDetails:\n" . $request->message;
        }

        Complaint::create([
            'user_id'  => Auth::id(), 
            'category' => $request->category,
            'message'  => $finalMessage,
            'status'   => 'pending',  
        ]);

        return redirect()->back()->with('success', 'Complaint submitted successfully!');
    }
}