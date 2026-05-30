<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Alert; 
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        // جلب التنبيهات الخاصة بولي الأمر الحالي باستخدام parent_id
        $alerts = Alert::where('parent_id', auth()->id())
            ->latest()
            ->get();

        return view('parents.alerts', compact('alerts'));
    }

    public function saveResponse(\Illuminate\Http\Request $request, $id)
    {
        $alert = \App\Models\Alert::findOrFail($id);
        $alert->parent_response = $request->answer; 
        $alert->save();

        return response()->json(['status' => 'success']);
    }



    public function updateResponse(Request $request, $id)
    {
        // التحقق من أن القيمة القادمة إما yes أو no
        $request->validate([
            'parent_response' => 'required|in:yes,no'
        ]);

        // جلب التنبيه وتحديث الحقل
        $alert = Alert::findOrFail($id);
        $alert->parent_response = $request->parent_response;
        $alert->save();

        // إعادة توجيه لولي الأمر لنفس الصفحة بعد الحفظ
        return back()->with('success', 'Response updated successfully');
    }
}