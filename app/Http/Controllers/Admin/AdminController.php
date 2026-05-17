<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Child;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use App\Models\DoctorRequest;
use App\Models\Complaint;

class AdminController extends Controller
{
    public function index()
    {
        // Users count
        $usersCount = User::count();

        // Doctors & parents count by role
        $doctorsCount = User::where('role', 'doctor')->count();
        $parentsCount = User::where('role', 'parent')->count();

        // Children count
        $childrenCount = Child::count();

        // Pending doctor requests
        $pendingRequestsCount = DoctorRequest::where('status', 'pending')->count();

        // Children count by autism level
        $mild = Child::where('autism_level', 'Mild')->count();
        $moderate = Child::where('autism_level', 'Moderate')->count();
        $severe = Child::where('autism_level', 'Severe')->count();

        // Last 7 days complaints count
        $complaintsData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = now()->subDays($i)->format('Y-m-d');

            $count = Complaint::whereDate('created_at', $date)->count();

            $complaintsData[] = $count;
        }

        // Send data to view
        return view('admin.dashboard', compact(
            'usersCount',
            'doctorsCount',
            'parentsCount',
            'childrenCount',
            'pendingRequestsCount',
            'mild',
            'moderate',
            'severe',
            'complaintsData'
        ));
    }
}