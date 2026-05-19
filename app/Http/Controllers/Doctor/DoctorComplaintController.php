<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // نفس جدول الشكاوى
use Illuminate\Support\Facades\Auth;

class DoctorComplaintController extends Controller
{
    // 1. عرض واجهة الشكاوى للدكتور
    public function create()
    {
        return view('doctor.complaint');
    }

    // 2. حفظ شكوى الدكتور
 public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string|max:5000',
            'parent_info' => 'nullable|string|max:255',
        ]);

        // دمج نوع الشكوى بشكل افتراضي في بداية أي رسالة
        $finalMessage = "Category: [" . $request->category . "]\n\nDetails:\n" . $request->message;

        // تخصيص الرسالة إذا كانت الشكوى ضد ولي أمر
        if ($request->category == 'Parent Dispute' && $request->parent_info) {
            $finalMessage = "Notice: Complaint against Parent (" . $request->parent_info . ")\n\nDetails:\n" . $request->message;
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