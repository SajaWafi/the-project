<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorRequestManagementController extends Controller
{
    // ---------------------------------------------------------
    // 1. دالة العرض (Index)
    // ---------------------------------------------------------
 public function index(Request $request)
{
    // جلب الطلبات مع العلاقات الأساسية لتجنب مشكلة N+1
    $query = \App\Models\DoctorRequest::with(['doctor.user', 'parent.user', 'parent.children'])->latest();

    // 💡 1. فلترة البحث (نبحث في اسم الدكتور أو اسم الأب)
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        
        $query->where(function($q) use ($searchTerm) {
            // البحث داخل علاقة الدكتور
            $q->whereHas('doctor.user', function($subQ) use ($searchTerm) {
                $subQ->where('first_name', 'like', "%{$searchTerm}%")
                     ->orWhere('last_name', 'like', "%{$searchTerm}%");
            })
            // أو البحث داخل علاقة ولي الأمر
            ->orWhereHas('parent.user', function($subQ) use ($searchTerm) {
                $subQ->where('first_name', 'like', "%{$searchTerm}%")
                     ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        });
    }

    // 💡 2. فلترة حالة الطلب (Pending, Accepted, Rejected)
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    // جلب البيانات مع الحفاظ على متغيرات الـ URL للصفحات التالية
    $requests = $query->paginate(10)->appends($request->query());

 return view('admin.doctor_requests_management', compact('requests'));
}

    public function accept($id)
    {
        $requestItem = DoctorRequest::with(['parent.children'])
            ->findOrFail($id);
        // [Database Transaction]: تفعيل وضع المعاملات لضمان عدم حفظ بيانات ناقصة
        DB::beginTransaction();

        try {
            $child = $requestItem->parent?->children?->first();

            if (!$child) {
                return back()->withErrors([
                    'child' => 'No child found for this parent.',
                ]);
            }
        //[Pivot Table Check]: التأكد هل الدكتور والطفل مربوطين مع بعض من قبل في جدول (child_doctor) الوسيط؟
            $alreadyLinked = DB::table('child_doctor')
                ->where('child_id', $child->id)
                ->where('doctor_id', $requestItem->doctor_id)
                ->exists();
        //[Raw Query Builder]: لو مش مربوطين، نربطهم يدوياً بإدخال سجل في الجدول الوسيط
            if (!$alreadyLinked) {
                DB::table('child_doctor')->insert([
                    'child_id' => $child->id,
                    'doctor_id' => $requestItem->doctor_id,
                ]);
            }

            $requestItem->update([
                'status' => 'accepted',
            ]);

            DB::commit();

            return back()->with('success', 'Request accepted and relationship fixed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
    // ---------------------------------------------------------
    // 3. دالة الرفض (Reject)
    // ---------------------------------------------------------
    public function reject($id)
    {
        $requestItem = DoctorRequest::findOrFail($id);
        // تغيير الحالة فقط بدون مسح الطلب ليبقى في الأرشيف
        $requestItem->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Request rejected successfully.');
    }
    // ---------------------------------------------------------
    // 4. دالة الحذف (Destroy)
    // ---------------------------------------------------------
    public function destroy($id)
    {
        $requestItem = DoctorRequest::findOrFail($id);
        $requestItem->delete();

        return back()->with('success', 'Request deleted successfully.');
    }
}