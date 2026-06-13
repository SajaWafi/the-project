<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    // ---------------------------------------------------------
    // 1. دالة إضافة الشكوى (Store)
    // ---------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'pending', //(Default State)
        ]);

        return back();
    }
    // ---------------------------------------------------------
    // 2. دالة العرض (Index)
    // ---------------------------------------------------------
    public function index()
    {
        $complaints = Complaint::with('user')->latest()->get();

        return view('admin.complaints_managment', compact('complaints'));
    }
    // ---------------------------------------------------------
    // 3. دالة التعديل (Update) 
    // ---------------------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved',
        ]);

        $complaint = Complaint::findOrFail($id);

        $complaint->status = $request->status;
        $complaint->save();
    //[JSON Response]: إرجاع النتيجة كـ JSON لأن هذه الدالة تُستدعى عبر الجافاسكربت 
        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully'
        ]);
    }
    // ---------------------------------------------------------
    // 4. دالة الحذف (Destroy)
    // ---------------------------------------------------------
    public function destroy($id)
    {
        Complaint::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Complaint deleted');
    }
}