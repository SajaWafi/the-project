<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorApprovedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'doctor') {
            $doctorProfile = $user->doctorProfile;

            if (!$doctorProfile || $doctorProfile->approval_status !== 'approved') {
                return redirect()
                    ->route('doctor.pending.approval')
                    ->with('error', 'Your account is waiting for admin approval.');
            }
        }

        return $next($request);
    }
}
