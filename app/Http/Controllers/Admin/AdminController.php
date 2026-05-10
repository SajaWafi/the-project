<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Child;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use App\Models\DoctorRequest;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $doctorsCount = DoctorProfile::count();
        $parentsCount = ParentProfile::count();
        $childrenCount = Child::count();
        $pendingRequestsCount = DoctorRequest::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'usersCount',
            'doctorsCount',
            'parentsCount',
            'childrenCount',
            'pendingRequestsCount'
        ));
    }
}