<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bracelet;
use Illuminate\Http\Request;

class BraceletController extends Controller
{
    // ---------------------------------------------------------
    // 1. دالة العرض (Index)
    // ---------------------------------------------------------
    public function index()
    {
        // 💡 [Eager Loading]: جلب كل الأسوارات مع بيانات الطفل المربوط بيها (إن وُجد) لمنع مشكلة N+1
        $bracelets = Bracelet::with('child')->get();
        return view('admin.bracelets', compact('bracelets'));
    }

    // ---------------------------------------------------------
    // 2. دالة الإضافة (Store)
    // ---------------------------------------------------------
    public function store(Request $request)
    {
        // 💡 [Data Validation]: التأكد من أن الحالة المدخلة صحيحة فقط (نشط أو غير نشط)
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        // 💡 [Auto-Increment PK]: ننشئ السوار بالحالة فقط، والـ ID (الذي قد يمثل الرقم التسلسلي) يتولد تلقائياً
        Bracelet::create([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Bracelet added successfully!');
    }

    // ---------------------------------------------------------
    // 3. دالة التعديل (Update)
    // ---------------------------------------------------------
    public function update(Request $request, $id)
    {
        $bracelet = Bracelet::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $bracelet->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Bracelet updated successfully!');
    }

    // ---------------------------------------------------------
    // 4. دالة الحذف (Destroy) 
    // ---------------------------------------------------------
    public function destroy($id)
    {
        $bracelet = Bracelet::findOrFail($id);
        
        // 💡 [Foreign Key Cleanup & State Sync]: قبل مسح السوار، يجب فك ارتباطه بالطفل وتغيير حالة اتصال الطفل
        if ($bracelet->child) {
            $bracelet->child->update([
                'bracelet_id' => null, // فك الربط
                'is_bracelet_connected' => false // مزامنة حالة التطبيق
            ]);
        }

        // مسح السوار نهائياً بعد تنظيف العلاقات
        $bracelet->delete();

        return back()->with('success', 'Bracelet deleted successfully!');
    }
}