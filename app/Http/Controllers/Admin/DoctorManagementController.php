<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorManagementController extends Controller
{
    // ---------------------------------------------------------
    // 1. دالة العرض (Index)
    // ---------------------------------------------------------
public function index(Request $request)
    {
        // 1. بداية الاستعلام (نجيبوا ملفات الأطباء مع بيانات المستخدم)
        $query = \App\Models\DoctorProfile::with('user')->latest();

        // 2. فلترة حالة القبول (هذا اللي كان مفقود أو ما يخدمش عندك!)
        if ($request->filled('approval') && $request->approval !== 'all') {
            // 💡 ملاحظة: تأكدي إن اسم الحقل في جدول الداتا بيز هو فعلاً 'approval_status'
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
    //[Database Transaction]: تفعيل المعاملات لأننا سنقوم بتحديث جدولين مختلفين (users و doctor_profiles)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $doctor = DoctorProfile::with('user')->findOrFail($id);
    //[Database Transaction]: تفعيل المعاملات لأننا سنقوم بتحديث جدولين مختلفين (users و doctor_profiles)
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
            if ($doctor->user) {
                $doctor->user->delete();
            } else {
                $doctor->delete(); // كود احتياطي (Fallback) لو كان البروفايل يتيم بدون User
            }

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
        $doctor = DoctorProfile::findOrFail($id);

        $doctor->update([
            'approval_status' => 'approved',
        ]);

        return back()->with('success', 'Doctor approved successfully.');
    }

    public function reject($id)
    {
        $doctor = DoctorProfile::findOrFail($id);

        $doctor->update([
            'approval_status' => 'rejected',
        ]);

        return back()->with('success', 'Doctor rejected successfully.');
    }

}