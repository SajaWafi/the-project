<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // الموديل الجاهز عندكِ في قاعدة البيانات
use Illuminate\Support\Facades\Auth;

class ParentComplaintController extends Controller
{
    public function create()
    {
        return view('complaint');
    }

   public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string|max:5000',
            'doctor_info' => 'nullable|string|max:255', 
        ]);

        $finalMessage = $request->message;

        // complaint against doctor
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