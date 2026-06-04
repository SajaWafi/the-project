<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;

class BraceletController extends Controller
{
    // عرض صفحة الربط
    public function showConnectBracelet()
    {
        $child = Child::whereHas('parentProfile', function($q) {
            $q->where('user_id', auth()->id());
        })->first();

        return view('parents.connect-bracelet', compact('child'));
    }

    // تنفيذ عملية الربط
    public function connectBracelet(Request $request)
    {
        $request->validate(['bracelet_id' => 'required|string|max:255']);
        
        $child = Child::whereHas('parentProfile', function($q) {
            $q->where('user_id', auth()->id());
        })->first();

        if ($child) {
            $child->update([
                'bracelet_id' => $request->bracelet_id,
                'is_bracelet_connected' => true
            ]);
            return back()->with('success', 'Smart Bracelet connected successfully!');
        }

        return back()->with('error', 'Child profile not found.');
    }

    // تنفيذ عملية الفصل
    public function disconnectBracelet()
    {
        $child = Child::whereHas('parentProfile', function($q) {
            $q->where('user_id', auth()->id());
        })->first();

        if ($child) {
            $child->update([
                'bracelet_id' => null,
                'is_bracelet_connected' => false
            ]);
            return back()->with('success', 'Smart Bracelet disconnected.');
        }

        return back()->with('error', 'Child profile not found.');
    }
}