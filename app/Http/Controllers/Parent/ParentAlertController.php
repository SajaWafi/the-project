<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;

class ParentAlertController extends Controller
{
    public function index()
    {
        $parent = auth()->user()->parentProfile;

        $alerts = Alert::where('parent_id', $parent->id)
            ->latest()
            ->get();

        return view('parents.alerts', compact('alerts'));
    }
}