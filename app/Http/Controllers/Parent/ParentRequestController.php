<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;

use App\Models\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentRequestController extends Controller
{
    // دالة قبول الطلب
    public function accept($id)
    {
        // التأكد إن الطلب مبعوث لهذا الأب بالذات
        $request = DoctorRequest::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        
        $request->update(['status' => 'accepted']);

        // هنا لاحقاً تقدر تضيف كود لإنشاء رابط في جدول doctor_parent_links

        return back()->with('success', 'تم قبول طلب الدكتور بنجاح.');
    }

    // دالة رفض الطلب
    public function reject($id)
    {
        $request = DoctorRequest::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        
        $request->update(['status' => 'rejected']);

        return back()->with('success', 'تم رفض الطلب.');
    }
}
  