<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentRequestController extends Controller
{
    // 1. دالة عرض الطلبات والإشعارات للأب
    public function index()
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(404, 'Parent profile not found.');
        }

        // 1. جلب الطلبات المعلقة (الكروت الخضراء)
        $requests = DoctorRequest::with('doctor.user')
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->unique('doctor_id'); // منع تكرار الطلبات من نفس الدكتور

        // 2. جلب إشعارات الشات (المربعات الزرقاء)
        $notifications = Notification::where('user_id', Auth::id())
            ->where('type', 'chat_message')
            ->where('created_at', '>=', now()->subDays(3))
            ->latest()
            ->get()
            ->groupBy('related_id');

        // 3. جلب إشعارات المواعيد (المربع الأصفر)
        $appointmentNotices = Notification::where('user_id', Auth::id())
            ->where('type', 'appointment_update')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get();

        return view('parents.requests', compact('requests', 'notifications', 'appointmentNotices'));
    }

    // 2. دالة قبول الطلب
    public function accept($id)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $requestItem = DoctorRequest::where('id', $id)
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $child = $parent->children()->first();

        if (!$child) {
            return back()->withErrors(['child' => 'No child found for this parent.']);
        }

        // استخدام الـ Try-Catch مع الـ Transaction لحماية قاعدة البيانات
        try {
            DB::transaction(function () use ($child, $requestItem) {
                
                $alreadyLinked = DB::table('child_doctor')
                    ->where('child_id', $child->id)
                    ->where('doctor_id', $requestItem->doctor_id)
                    ->exists();

                if (!$alreadyLinked) {
                    DB::table('child_doctor')->insert([
                        'child_id' => $child->id,
                        'doctor_id' => $requestItem->doctor_id,
                    ]);
                }

                $requestItem->update(['status' => 'accepted']);
            });

            return back()->with('success', 'تم قبول طلب الدكتور بنجاح وتم ربط الطفل.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة الطلب، الرجاء المحاولة لاحقاً.']);
        }
    }

    // 3. دالة رفض الطلب
    public function reject($id)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $requestItem = DoctorRequest::where('id', $id)
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $requestItem->update(['status' => 'rejected']);

        return back()->with('success', 'تم رفض الطلب.');
    }
}