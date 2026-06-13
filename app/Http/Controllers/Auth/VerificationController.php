<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    // 1. عرض واجهة إدخال الكود
    public function show()
    {
        return view('auth.verify-email'); 
    }

    // 2. التحقق من الكود اللي دخله المستخدم
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();

        // هل الكود غلط؟
        if ($user->email_verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // هل الكود منتهي الصلاحية ؟
        if (now()->greaterThan($user->email_verification_code_expires_at)) {
            return back()->withErrors(['code' => 'The verification code has expired.']);
        }

        // لو كل شيء تمام، نحدثوا حالة المستخدم
        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null, // نمسحوا الكود باش معاش يستعمله
            'email_verification_code_expires_at' => null,
        ]);

        // توجيه المستخدم للداشبورد حسب نوع حسابه
        if ($user->role === 'parent') {
            return redirect()->route('parents.home')->with('success', 'Email verified successfully!');
        } elseif ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard')->with('success', 'Email verified successfully!');
        }

        return redirect('/')->with('success', 'Email verified!');
    }

    // 3. إعادة إرسال الكود
    public function resend()
    {
        $user = auth()->user();
        
        $code = rand(100000, 999999); // كود جديد
        
        $user->update([
            'email_verification_code' => $code,
            'email_verification_code_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
