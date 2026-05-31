<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Workplace;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $doctorProfile = $user->doctorProfile;

        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)->latest()->get();

        $appointments = Appointment::with(['parent.user', 'child'])
            ->where('doctor_id', $doctorProfile->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderByRaw("
                CASE 
                    WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                    WHEN from_period = 'AM' THEN from_hour
                    WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                    ELSE from_hour + 12
                END
            ")
            ->orderBy('from_minute')
            ->get();

        return view('doctor.home', compact('workplaces', 'appointments'));
    }
}