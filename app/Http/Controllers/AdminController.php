<?php
namespace App\Http\Controllers;

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
        $doctorsCount = User::where('role', 'doctor')->count();
        $parentsCount = User::where('role', 'parent')->count();
        $childrenCount = Child::count();
        $pendingRequestsCount = 0;

        return view('admin.dashboard', compact(
            'usersCount', 
            'doctorsCount', 
            'parentsCount', 
            'childrenCount', 
            'pendingRequestsCount'
        ));
    }
}