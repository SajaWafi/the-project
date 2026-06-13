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
    public function index()
    {
        $doctors = DoctorProfile::with('user')
            ->latest()
            ->paginate(10);

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
