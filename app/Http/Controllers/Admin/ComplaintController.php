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
    // 2. 💡 دالة العرض (Index) - تم التعديل للفلترة والبحث عبر السيرفر
    // ---------------------------------------------------------
    public function index(Request $request)
    {
        // جلب الشكاوى مع علاقة المستخدم
        $query = Complaint::with('user')->latest();

        // 1. البحث النصي (يبحث في محتوى الشكوى، أو اسم المستخدم، أو الإيميل)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('message', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($sub) use ($searchTerm) {
                      $sub->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%")
                          ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // 2. فلترة الدور (Doctor أو Parent)
        if ($request->filled('role') && $request->role !== 'all') {
            $role = $request->role;
            $query->whereHas('user', function($sub) use ($role) {
                $sub->where('role', $role);
            });
        }

        // 3. فلترة التصنيف (Category)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // تقسيم النتائج (Pagination) مع الحفاظ على الفلاتر في الروابط (15 شكوى في كل صفحة)
        $complaints = $query->paginate(15)->appends($request->query());

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