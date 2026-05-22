<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // نفس جدول الشكاوى
use Illuminate\Support\Facades\Auth;

class DoctorComplaintController extends Controller
{
    public function create()
    {
        return view('doctor.complaint');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string|max:5000',
            'parent_info' => 'nullable|string|max:255',
        ]);

        $finalMessage = $request->message;

        // complaint against parent
        if ($request->category == 'parent_dispute' && $request->parent_info) {

            $finalMessage =
                "Parent Info: " . $request->parent_info .
                "\n\n" .
                $request->message;
        }

        Complaint::create([
            'user_id'  => Auth::id(),
            'category' => $request->category,
            'message'  => $finalMessage,
            'status'   => 'pending',
        ]);

        return redirect()->back()->with(
            'success',
            'Complaint submitted successfully!'
        );
    }
}