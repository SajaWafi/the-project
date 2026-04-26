<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('parents.alerts', compact('alerts'));
    }

    public function test()
    {
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'panic_severe',
            'title' => 'WARNING: Severe Panic Attack Detected',
            'message' => 'Immediate intervention required. Please check the child now.',
        ]);

        return redirect()->route('parents.alerts');
    }
}