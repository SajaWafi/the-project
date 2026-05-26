<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Alert; 

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
}