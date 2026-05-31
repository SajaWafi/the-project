<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentRequestController extends Controller
{
    // 1. دالة عرض الطلبات للأب
    public function index()
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(404, 'Parent profile not found.');
        }

        $requests = DoctorRequest::with('doctor.user')
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('parents.requests', compact('requests'));
    }

    // 2. دالة قبول الطلب
    public function accept($id)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        // التأكد إن الطلب مبعوث لهذا الأب بالذات
        $requestItem = DoctorRequest::where('id', $id)
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $child = $parent->children()->first();

        if (!$child) {
            return back()->withErrors(['child' => 'No child found for this parent.']);
        }

        // التأكد إن العلاقة مش موجودة مسبقاً لتجنب التكرار
        $alreadyLinked = DB::table('child_doctor')
            ->where('child_id', $child->id)
            ->where('doctor_id', $requestItem->doctor_id)
            ->exists();

        // إنشاء الرابط بين الطفل والدكتور
        if (!$alreadyLinked) {
            DB::table('child_doctor')->insert([
                'child_id' => $child->id,
                'doctor_id' => $requestItem->doctor_id,
            ]);
        }

        $requestItem->update(['status' => 'accepted']);

        return back()->with('success', 'تم قبول طلب الدكتور بنجاح وتم ربط الطفل.');
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