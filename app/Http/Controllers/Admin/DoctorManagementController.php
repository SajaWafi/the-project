<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogsActivity; // 💡 استدعاء أداة التسجيل

class DoctorManagementController extends Controller
{
    use LogsActivity; // 💡 تفعيل التسجيل داخل الكنترولر

    // ---------------------------------------------------------
    // 1. دالة العرض (Index)
    // ---------------------------------------------------------
    public function index(Request $request)
    {
        // 1. بداية الاستعلام (نجيبوا ملفات الأطباء مع بيانات المستخدم)
        $query = \App\Models\DoctorProfile::with('user')->latest();

        // 2. فلترة حالة القبول
        if ($request->filled('approval') && $request->approval !== 'all') {
            $query->where('approval_status', $request->approval);
        }

        // 3. فلترة البحث بالاسم
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        }

        // 4. جلب البيانات مع الحفاظ على الفلاتر في الـ Pagination
        $doctors = $query->paginate(10)->appends($request->query());

        return view('admin.doctors_management', compact('doctors'));
    }

    // ---------------------------------------------------------
    // 2. دالة التعديل 
    // ---------------------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $doctor = DoctorProfile::with('user')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // تحديث البيانات في جدول الـ Users (الاسم)
            if ($doctor->user) {
                $doctor->user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);
            }

            $doctor->update([
                'specialization' => $request->specialization,
            ]);

            // 💡 تسجيل الحركة (تعديل)
            $this->logActivity('تعديل طبيب', "قام الأدمن بتحديث بيانات الطبيب: {$request->first_name} {$request->last_name}");

            DB::commit();

            return back()->with('success', 'Doctor updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // ---------------------------------------------------------
    // 3. دالة الحذف (Destroy)
    // ---------------------------------------------------------
    public function destroy($id)
    {
        $doctor = DoctorProfile::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            // حفظ الاسم قبل الحذف للتسجيل
            $doctorName = $doctor->user ? $doctor->user->first_name . ' ' . $doctor->user->last_name : 'طبيب غير معروف';

            if ($doctor->user) {
                $doctor->user->delete();
            } else {
                $doctor->delete(); // كود احتياطي
            }

            // 💡 تسجيل الحركة (حذف)
            $this->logActivity('حذف طبيب', "قام الأدمن بحذف حساب الطبيب: {$doctorName}");

            DB::commit();

            return back()->with('success', 'Doctor deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // ---------------------------------------------------------
    // 4. دالة الإضافة 
    // ---------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'gender' => 'nullable|in:Male,Female',
            'password' => 'required|string|min:6',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'role' => 'doctor',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
                'approval_status' => 'approved',
            ]);

            DoctorProfile::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'bio' => $request->bio,
            ]);

            // 💡 تسجيل الحركة (إضافة)
            $this->logActivity('إضافة طبيب', "قام الأدمن بإنشاء حساب جديد للطبيب: {$request->first_name} {$request->last_name}");

            DB::commit();

            return back()->with('success', 'Doctor added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // ---------------------------------------------------------
    // 5. دوال تغيير حالة الموافقة (Approve & Reject)
    // ---------------------------------------------------------
    public function approve($id)
    {
        $doctor = DoctorProfile::with('user')->findOrFail($id);

        $doctor->update([
            'approval_status' => 'approved',
        ]);

        $doctorName = $doctor->user ? $doctor->user->first_name . ' ' . $doctor->user->last_name : 'غير معروف';
        
        // 💡 تسجيل الحركة (قبول)
        $this->logActivity('موافقة على طبيب', "قام الأدمن بقبول تسجيل الطبيب: {$doctorName}");

        return back()->with('success', 'Doctor approved successfully.');
    }

    public function reject($id)
    {
        $doctor = DoctorProfile::with('user')->findOrFail($id);

        $doctor->update([
            'approval_status' => 'rejected',
        ]);

        $doctorName = $doctor->user ? $doctor->user->first_name . ' ' . $doctor->user->last_name : 'غير معروف';
        
        // 💡 تسجيل الحركة (رفض)
        $this->logActivity('رفض طبيب', "قام الأدمن برفض تسجيل الطبيب: {$doctorName}");

        return back()->with('success', 'Doctor rejected successfully.');
    }
}