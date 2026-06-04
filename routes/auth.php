<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\ParentProfile;
use App\Models\Child;
use App\Models\DoctorProfile;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DoctorManagementController;
use App\Mail\ResetPasswordMail;

// =======================
// Welcome Screens
// =======================
Route::get('/', function () {
    return view('welcome-screen');
})->name('welcome');

Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');

Route::get('/doctor/pending-approval', function () {
    return view('doctor.pending-approval');
})->name('doctor.pending.approval');


// =======================
// Login Page & Logic
// =======================
Route::get('/login', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'parent') {
            return redirect()->route('parents.home');
        }

        if ($user->role === 'doctor') {
            if (!$user->doctorProfile || $user->doctorProfile->approval_status !== 'approved') {
                Auth::logout();
                return redirect()->route('login.page')->withErrors([
                    'email' => 'Your doctor account is waiting for admin approval.',
                ]);
            }
            return redirect()->route('doctor.home');
        }

        return redirect()->route('welcome');
    }

    return view('login-page');
})->name('login.page');


Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->has('remember');

    if (!Auth::attempt($credentials, $remember)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'parent') {
        return redirect()->route('parents.home');
    }

    if ($user->role === 'doctor') {
        if (!$user->doctorProfile || $user->doctorProfile->approval_status !== 'approved') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your doctor account is waiting for admin approval.',
            ])->onlyInput('email');
        }
        return redirect()->route('doctor.home');
    }

    Auth::logout();
    return back()->withErrors([
        'email' => 'Invalid user role.',
    ])->onlyInput('email');
})->name('login.post');


// =======================
// Protected Home Pages
// =======================
Route::get('/parents/home', function () {
    if (!Auth::check()) return redirect()->route('login.page');
    if (Auth::user()->role !== 'parent') abort(403);
    return view('parents.home');
})->name('parents.home');


Route::get('/doctor/home', function () {
    if (!Auth::check()) return redirect()->route('login.page');
    if (Auth::user()->role !== 'doctor') abort(403);
    return view('doctor.home');
})->name('doctor.home');


// =======================
// Logout
// =======================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login.page');
})->name('logout');


// =======================
// Signup Choosing
// =======================
Route::get('/signup/choice', function () {
    return view('signup.choice');
})->name('signup.choice');


// =======================
// Parent Signup Flow
// =======================

// Step 1
Route::get('/signup/step1', function () {
    session()->forget('signup');
    return view('signup.step1');
})->name('signup.step1');

Route::post('/step1', function (Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',
        'relation_to_child' => 'required|string|max:255',
    ]);

    session([
        'signup.first_name' => $request->first_name,
        'signup.last_name' => $request->last_name,
        'signup.phone' => $request->phone,
        'signup.relation_to_child' => $request->relation_to_child,
    ]);

    return redirect()->route('step2');
})->name('step1.post');

// Step 2
Route::get('/step2', function () {
    return view('signup.step2');
})->name('step2');

Route::post('/step2', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|same:password_confirmation',
        'password_confirmation' => 'required|string|min:6',
    ]);

    $signup = session('signup', []);

    if (empty($signup['first_name']) || empty($signup['last_name']) || empty($signup['phone']) || empty($signup['relation_to_child'])) {
        return redirect()->route('signup.step1')->withErrors(['Please complete step 1 first.']);
    }

    // إنشاء الحساب مبدئياً لحين تفعيله
    $user = User::create([
        'role' => 'parent',
        'first_name' => $signup['first_name'],
        'last_name' => $signup['last_name'],
        'email' => $request->email,
        'phone' => $signup['phone'],
        'password' => Hash::make($request->password),
    ]);

    $parentProfile = ParentProfile::create([
        'user_id' => $user->id,
        'relation_to_child' => $signup['relation_to_child'],
    ]);

    session([
        'signup.email' => $request->email,
        'signup.password' => $request->password,
        'signup.user_id' => $user->id,
        'signup.parent_profile_id' => $parentProfile->id,
        'verification_user_id' => $user->id, // لحفظ الرقم للتحقق
    ]);

    // توليد الكود وإرساله
    $code = rand(100000, 999999);
    $user->update([
        'email_verification_code' => $code,
        'email_verification_code_expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($user->email)->send(new VerificationCodeMail($code));

    return redirect()->route('signup.verify');
})->name('signup.step2.post');

// Step 3 (بيانات الطفل - تفتح بعد التحقق بنجاح)
Route::get('/step3', function () {
    return view('signup.step3');
})->name('signup.step3');

Route::post('/step3', function (Request $request) {
    $request->validate([
        'child_name' => 'required|string|max:255',
        'gender' => 'required|in:Male,Female',
        'dob' => 'required|date',
    ]);

    session([
        'signup.child_name' => $request->child_name,
        'signup.child_gender' => $request->gender,
        'signup.child_birth_date' => $request->dob,
    ]);

    return redirect()->route('signup.step4');
})->name('signup.step3.post');

// Step 4
Route::get('/step4', function () {
    return view('signup.step4');
})->name('signup.step4');

Route::post('/step4', function (Request $request) {
    $request->validate([
        'autism_level' => 'required|in:Mild,Moderate,Severe',
    ]);

    $parentProfileId = session('signup.parent_profile_id');
    $childName = session('signup.child_name');
    $childGender = session('signup.child_gender');
    $childBirthDate = session('signup.child_birth_date');

    if (!$parentProfileId) {
        return redirect()->route('signup.step1')->withErrors(['Please complete parent signup first.']);
    }

    if (!$childName || !$childGender || !$childBirthDate) {
        return redirect()->route('signup.step3')->withErrors(['Please complete child information first.']);
    }

    Child::create([
        'parent_id' => $parentProfileId,
        'name' => $childName,
        'gender' => $childGender,
        'birth_date' => $childBirthDate,
        'autism_level' => $request->autism_level,
    ]);

    $user = User::find(session('signup.user_id'));
    Auth::login($user);

    session()->forget('signup');
    session()->forget('verification_user_id');

    return redirect()->route('parents.home');
})->name('signup.step4.post');


// =======================
// Doctor Signup Flow
// =======================

// Step 1
Route::get('/doctor/signup/step1', function () {
    session()->forget('doctor_signup');
    return view('doctor-signup.step1');
})->name('doctor.step1');

Route::post('/doctor/signup/step1', function (Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
    ]);

    session([
        'doctor_signup.first_name' => $request->first_name,
        'doctor_signup.last_name' => $request->last_name,
        'doctor_signup.email' => $request->email,
    ]);

    return redirect()->route('doctor.step2');
})->name('doctor.step1.post');

// Step 2
Route::get('/doctor/signup/step2', function () {
    return view('doctor-signup.step2');
})->name('doctor.step2');

Route::post('/doctor/signup/step2', function (Request $request) {
    $request->validate([
        'password' => 'required|string|min:6|same:password_confirmation',
        'password_confirmation' => 'required|string|min:6',
    ]);

    session([
        'doctor_signup.password' => $request->password,
    ]);

    return redirect()->route('doctor.step3');
})->name('doctor.step2.post');

// Step 3
Route::get('/doctor/signup/step3', function () {
    return view('doctor-signup.step3');
})->name('doctor.step3');

Route::post('/doctor/signup/step3', function (Request $request) {
    $request->validate([
        'phone' => 'required|string|max:30',
        'gender' => 'required|in:Male,Female',
        'specialize' => 'required|string|max:255',
        'dob' => 'required|date',
        'bio' => 'nullable|string|max:1000',
    ]);

    $signup = session('doctor_signup', []);

    if (empty($signup['first_name']) || empty($signup['last_name']) || empty($signup['email']) || empty($signup['password'])) {
        return redirect()->route('doctor.step1')->withErrors(['Please complete signup steps first.']);
    }

    $user = User::create([
        'role' => 'doctor',
        'first_name' => $signup['first_name'],
        'last_name' => $signup['last_name'],
        'email' => $signup['email'],
        'phone' => $request->phone,
        'gender' => $request->gender,
        'password' => Hash::make($signup['password']),
    ]);

    DoctorProfile::create([
        'user_id' => $user->id,
        'birth_date' => $request->dob,
        'specialization' => $request->specialize,
        'bio' => $request->bio ?? null,
        'approval_status' => 'pending',
    ]);

    session(['verification_user_id' => $user->id]);

    // توليد الكود وإرساله للدكتور
    $code = rand(100000, 999999);
    $user->update([
        'email_verification_code' => $code,
        'email_verification_code_expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($user->email)->send(new VerificationCodeMail($code));
    session()->forget('doctor_signup');

    return redirect()->route('signup.verify');
})->name('doctor.step3.post');


// =======================
// Email Verification Logic
// =======================
Route::get('/signup/verify', function () {
    if (!session()->has('verification_user_id')) {
        return redirect()->route('welcome');
    }
    return view('auth.verify-email');
})->name('signup.verify');

Route::post('/signup/verify', function (Request $request) {
    $request->validate([
        'code' => 'required|numeric|digits:6'
    ]);

    $user = User::find(session('verification_user_id'));

    if (!$user || $user->email_verification_code !== $request->code) {
        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    if (now()->greaterThan($user->email_verification_code_expires_at)) {
        return back()->withErrors(['code' => 'The verification code has expired.']);
    }

    // تفعيل حساب المستخدم بنجاح
    $user->update([
        'email_verified_at' => now(),
        'email_verification_code' => null,
        'email_verification_code_expires_at' => null,
    ]);

    // إذا كان دكتور، يذهب إلى شاشة الانتظار
    if ($user->role === 'doctor') {
        session()->forget('verification_user_id');
        return redirect()->route('doctor.pending.approval')
            ->with('success', 'Email verified! Your account is waiting for admin approval.');
    }

    // إذا كان ولي أمر، يكمل خطوة الطفل الثالثة
    return redirect()->route('signup.step3')->with('success', 'Email verified successfully! Please complete your child information.');
})->name('verify.email.submit');

Route::post('/signup/verify/resend', function () {
    $user = User::find(session('verification_user_id'));
    if (!$user) return back();

    $code = rand(100000, 999999);
    $user->update([
        'email_verification_code' => $code,
        'email_verification_code_expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($user->email)->send(new VerificationCodeMail($code));

    return back()->with('success', 'A new verification code has been sent to your email.');
})->name('verify.email.resend');

// =======================
// Forgot Password Flow
// =======================

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email|exists:users,email']);
    
    $user = \App\Models\User::where('email', $request->email)->first();
    
    $code = rand(100000, 999999);
    $user->update([
        'reset_code' => $code,
        'reset_code_expires_at' => now()->addMinutes(10),
    ]);

    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($code));
    session(['reset_email' => $user->email]);

    return redirect()->route('reset.password.show');
})->name('forgot.password.post');

Route::get('/reset-password', function () {
    if (!session()->has('reset_email')) return redirect()->route('forgot.password');
    return view('auth.reset-password');
})->name('reset.password.show');

Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'code' => 'required|numeric|digits:6',
        'password' => 'required|string|min:6|same:password_confirmation',
        'password_confirmation' => 'required|string|min:6',
    ]);

    $user = \App\Models\User::where('email', session('reset_email'))->first();

    if (!$user || $user->reset_code !== $request->code) {
        return back()->withErrors(['code' => 'Invalid reset code.']);
    }

    if (now()->greaterThan($user->reset_code_expires_at)) {
        return back()->withErrors(['code' => 'The reset code has expired.']);
    }

    $user->update([
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'reset_code' => null,
        'reset_code_expires_at' => null,
    ]);

    session()->forget('reset_email');
    return redirect()->route('login.page')->with('success', 'Password reset successfully! You can now log in.');
})->name('reset.password.post');