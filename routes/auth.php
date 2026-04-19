<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\ParentProfile;
use App\Models\Child;
use App\Models\DoctorProfile;

// Welcome
Route::get('/', function () {
    return view('welcome-screen');
})->name('welcome');

Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');

//login page
Route::get('/login', function () {
    return view('login-page');
})->name('login.page');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->role === 'parent') {
        return redirect()->route('parents.home');
    }

    if ($user->role === 'doctor') {
        return redirect()->route('doctor.home');
    }

    return redirect('/');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.page');
})->name('logout');

// Login
/*Route::get('/login-page', function () {
    return view('login-page');
})->name('login.page');
*/

//login chosing
Route::get('/signup/choice', function () {
    return view('signup.choice');
})->name('signup.choice');


// Parent signup

// step1
Route::get('/signup/step1', function () {
    session()->forget('signup.email');
    session()->forget('signup.password');
    session()->forget('signup.user_id');
    session()->forget('signup.parent_profile_id');
    session()->forget('signup.child_name');
    session()->forget('signup.child_gender');
    session()->forget('signup.child_birth_date');
    session()->forget('signup.autism_level');
    session()->forget('signup.child_id');

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

// step2
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

    if (
        empty($signup['first_name']) ||
        empty($signup['last_name']) ||
        empty($signup['phone']) ||
        empty($signup['relation_to_child'])
    ) {
        return redirect()->route('signup.step1')
            ->withErrors(['Please complete step 1 first.']);
    }

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
    ]);

    return redirect()->route('signup.step3');
})->name('signup.step2.post');

// child info
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

// step4
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
        return redirect()->route('signup.step1')
            ->withErrors(['Please complete parent signup first.']);
    }

    if (!$childName || !$childGender || !$childBirthDate) {
        return redirect()->route('signup.step3')
            ->withErrors(['Please complete child information first.']);
    }

    $child = Child::create([
        'parent_id' => $parentProfileId,
        'name' => $childName,
        'gender' => $childGender,
        'birth_date' => $childBirthDate,
        'autism_level' => $request->autism_level,
    ]);

    $user = User::find(session('signup.user_id'));

    Auth::login($user);

    session()->forget('signup');

    return redirect()->route('parents.home');
})->name('signup.step4.post');

// Doctor signup

// step1
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


// step2
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


// step3
Route::get('/doctor/signup/step3', function () {
    return view('doctor-signup.step3');
})->name('doctor.step3');

Route::post('/doctor/signup/step3', function (Request $request) {
    $request->validate([
        'phone' => 'required|string|max:30',
        'gender' => 'required|in:Male,Female',
        'specialize' => 'required|string|max:255',
        'dob' => 'required|date',
    ]);

    session([
        'doctor_signup.phone' => $request->phone,
        'doctor_signup.gender' => $request->gender,
        'doctor_signup.specialization' => $request->specialize,
        'doctor_signup.birth_date' => $request->dob,
        'doctor_signup.bio' => $request->bio,
    ]);

    $signup = session('doctor_signup', []);

    if (
        empty($signup['first_name']) ||
        empty($signup['last_name']) ||
        empty($signup['email']) ||
        empty($signup['password'])
    ) {
        return redirect()->route('doctor.signup.step1')
            ->withErrors(['Please complete step 1 first.']);
    }

    $user = User::create([
        'role' => 'doctor',
        'first_name' => $signup['first_name'],
        'last_name' => $signup['last_name'],
        'email' => $signup['email'],
        'phone' => $signup['phone'],
        'gender' => $signup['gender'],
        'password' => Hash::make($signup['password']),
    ]);

    $doctorProfile = DoctorProfile::create([
        'user_id' => $user->id,
        'birth_date' => $signup['birth_date'],
        'specialization' => $signup['specialization'],
        'bio' => $signup['bio'] ?? null,
    ]);

    session([
        'doctor_signup.user_id' => $user->id,
        'doctor_signup.doctor_profile_id' => $doctorProfile->id,
    ]);

    Auth::login($user);
    session()->forget('doctor_signup');

    return redirect()->route('doctor.home');
})->name('doctor.step3.post');


Route::post('/logout', function () {
    Auth::logout();


    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login'); // أو الصفحة الرئيسية
})->name('logout');





