<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bracelet;
use App\Models\Child;
use Illuminate\Http\Request;
use App\Traits\LogsActivity; // 💡 استدعاء أداة التسجيل

class BraceletController extends Controller
{
    use LogsActivity; // 💡 تفعيل التسجيل داخل الكنترولر

    public function index()
    {
        $bracelets = Bracelet::with('child')->get();
        
        // 💡 نجيبوا كل الأطفال، ونجيبوا مصفوفة بأرقام الأطفال المربوطين حالياً
        $children = Child::all(); 
        $assignedChildIds = Bracelet::whereNotNull('child_id')->pluck('child_id')->toArray();

        $totalBracelets = $bracelets->count();
        $assignedBracelets = count($assignedChildIds);
        $unassignedBracelets = $totalBracelets - $assignedBracelets;

        return view('admin.bracelets', compact('bracelets', 'children', 'assignedChildIds', 'totalBracelets', 'assignedBracelets', 'unassignedBracelets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:bracelets,serial_number',
            'status' => 'required|in:active,inactive',
        ]);

        Bracelet::create([
            'serial_number' => $request->serial_number,
            'status' => $request->status,
        ]);

        // 💡 تسجيل الحركة (إضافة إسوارة)
        $this->logActivity('إضافة إسوارة', "قام الأدمن بإضافة إسوارة ذكية جديدة للنظام برقم تسلسلي: {$request->serial_number}");

        return back()->with('success', 'Bracelet added successfully!');
    }

    public function update(Request $request, $id)
    {
        $bracelet = Bracelet::findOrFail($id);

        // 💡 حماية الباك-إند: نمنع اختيار طفل مربوط مسبقاً بإسوارة ثانية
        $request->validate([
            'serial_number' => 'required|string|unique:bracelets,serial_number,' . $id,
            'status' => 'required|in:active,inactive',
            'child_id' => 'nullable|exists:children,id|unique:bracelets,child_id,' . $id
        ]);

        $oldChildId = $bracelet->child_id;
        $newChildId = $request->child_id;

        $bracelet->update([
            'serial_number' => $request->serial_number,
            'status' => $request->status,
            'child_id' => $newChildId,
        ]);

        // 💡 تنظيف بيانات الطفل القديم (لو غيرنا الطفل)
        if ($oldChildId && $oldChildId != $newChildId) {
            Child::where('id', $oldChildId)->update(['bracelet_id' => null, 'is_bracelet_connected' => false]);
        }

        // 💡 تحديث بيانات الطفل الجديد
        $childName = 'غير معروف';
        if ($newChildId) {
            $child = Child::find($newChildId);
            $childName = $child->name ?? 'غير معروف';
            
            $child->update([
                'bracelet_id' => $bracelet->id, 
                'is_bracelet_connected' => ($request->status === 'active')
            ]);
        }

        // 💡 تسجيل الحركة (تعديل/ربط إسوارة)
        if ($oldChildId != $newChildId && $newChildId != null) {
            $this->logActivity('تعديل إسوارة', "قام الأدمن بربط الإسوارة رقم ({$request->serial_number}) بالطفل: {$childName}");
        } else {
            $this->logActivity('تعديل إسوارة', "قام الأدمن بتحديث بيانات الإسوارة رقم: {$request->serial_number}");
        }

        return back()->with('success', 'Bracelet updated successfully!');
    }

    public function unlink($id)
    {
        $bracelet = Bracelet::findOrFail($id);
        
        $childName = $bracelet->child ? $bracelet->child->name : 'طفل غير معروف';
        $serialNumber = $bracelet->serial_number;

        if ($bracelet->child) {
            $bracelet->child->update(['bracelet_id' => null, 'is_bracelet_connected' => false]);
        }

        $bracelet->update(['child_id' => null]);

        // 💡 تسجيل الحركة (إلغاء ربط)
        $this->logActivity('إلغاء ربط إسوارة', "قام الأدمن بفك ارتباط الإسوارة رقم ({$serialNumber}) عن الطفل: {$childName} وإرجاعها للمخزن");

        return back()->with('success', 'Bracelet unlinked successfully! It is now in stock.');
    }

    public function destroy($id)
    {
        $bracelet = Bracelet::findOrFail($id);
        
        $serialNumber = $bracelet->serial_number;

        if ($bracelet->child) {
            $bracelet->child->update(['bracelet_id' => null, 'is_bracelet_connected' => false]);
        }

        $bracelet->delete();

        // 💡 تسجيل الحركة (حذف إسوارة)
        $this->logActivity('حذف إسوارة', "قام الأدمن بحذف الإسوارة رقم ({$serialNumber}) من المنظومة بشكل نهائي");

        return back()->with('success', 'Bracelet deleted successfully!');
    }
}