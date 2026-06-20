<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    // show verification page
    public function show()
    {
        return view('auth.verify-email'); 
    }

    // verify code
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();

        // check if code is correct or not
        if ($user->email_verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // check if code is expired or not
        if (now()->greaterThan($user->email_verification_code_expires_at)) {
            return back()->withErrors(['code' => 'The verification code has expired.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null, 
            'email_verification_code_expires_at' => null,
        ]);

        
        if ($user->role === 'parent') {
            return redirect()->route('parents.home')->with('success', 'Email verified successfully!');
        } elseif ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard')->with('success', 'Email verified successfully!');
        }

        return redirect('/')->with('success', 'Email verified!');
    }

    // resend code
    public function resend()
    {
        $user = auth()->user();
        
        $code = rand(100000, 999999); 
        
        $user->update([
            'email_verification_code' => $code,
            'email_verification_code_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
