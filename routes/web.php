<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/parent.php';
require __DIR__ . '/doctor.php';
/*
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

/*
use App\Models\User;
use App\Models\Child;
use App\Models\ParentProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


//Route::get('/', function () {
  //  return view('welcome');
//});
/*
//welcome screen
Route::get('/', function () {
    return view('welcome-screen');
});
Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');

// signup - parent

Route::get('/signup/step1', function () {
    return view('signup.step1');
})->name('signup.step1');

Route::post('/signup/step1', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',
        'gender' => 'nullable|in:Male,Female',
        'relation_to_child' => 'nullable|string|max:255',
        'agree' => 'required',
    ]);

    session([
        'signup.email' => $request->email,
        'signup.first_name' => $request->first_name,
        'signup.last_name' => $request->last_name,
        'signup.phone' => $request->phone,
        'signup.gender' => $request->gender,
        'signup.relation_to_child' => $request->relation_to_child,
    ]);

    return redirect()->route('signup.step2');
})->name('signup.step1.post');

Route::get('/signup/step2', function () {
    return view('signup.step2');
})->name('signup.step2');

Route::post('/signup/step2', function (Request $request) {
    $request->validate([
        'password' => 'required|string|min:6|same:password_confirmation',
        'password_confirmation' => 'required|string|min:6',
    ]);

    session([
        'signup.password' => $request->password,
    ]);

    return redirect()->route('signup.step3');
})->name('signup.step2.post');

Route::get('/signup/step3', function () {
    return view('signup.step3');
})->name('signup.step3');

Route::post('/signup/step3', function (Request $request) {
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

Route::get('/signup/step4', function () {
    return view('signup.step4');
})->name('signup.step4');

Route::post('/signup/step4', function (Request $request) {
    $request->validate([
        'autism_level' => 'required|in:Mild,Moderate,Severe',
    ]);

    session([
        'signup.autism_level' => $request->autism_level,
    ]);

    $signup = session('signup', []);

    if (
        empty($signup['email']) ||
        empty($signup['first_name']) ||
        empty($signup['last_name']) ||
        empty($signup['phone']) ||
        empty($signup['password']) ||
        empty($signup['child_name']) ||
        empty($signup['child_gender']) ||
        empty($signup['child_birth_date']) ||
        empty($signup['autism_level'])
    ) {
        return redirect()->route('signup.step1')
            ->withErrors(['Please complete all signup steps first.']);
    }

    $user = null;

    DB::transaction(function () use ($signup, &$user) {
        $user = User::create([
            'role' => 'parent',
            'first_name' => $signup['first_name'],
            'last_name' => $signup['last_name'],
            'email' => $signup['email'],
            'phone' => $signup['phone'],
            'gender' => $signup['gender'] ?? null,
            'password' => Hash::make($signup['password']),
        ]);

        $parentProfile = ParentProfile::create([
            'user_id' => $user->id,
            'relation_to_child' => $signup['relation_to_child'] ?? null,
        ]);

        Child::create([
            'parent_id' => $parentProfile->id,
            'name' => $signup['child_name'],
            'gender' => $signup['child_gender'],
            'birth_date' => $signup['child_birth_date'],
            'autism_level' => $signup['autism_level'],
        ]);
    });

    session()->forget('signup');

    Auth::login($user);

    return redirect()->route('parents.home')->with('success', 'Account created successfully.');
})->name('signup.step4.post');

//doctor signup
Route::get('/doctor/signup/step1', function () {
    return view('doctor-signup.step1');
})->name('doctor.signup.step1');

Route::post('/doctor/signup/step1', function (Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'agree' => 'required',
    ]);

    session([
        'doctor_signup.first_name' => $request->first_name,
        'doctor_signup.last_name' => $request->last_name,
        'doctor_signup.email' => $request->email,
    ]);

    return redirect()->route('doctor.signup.step2');
})->name('doctor.signup.step1.post');

Route::get('/doctor/signup/step2', function () {
    return view('doctor-signup.step2');
})->name('doctor.signup.step2');

Route::post('/doctor/signup/step2', function (Request $request) {
    $request->validate([
        'password' => 'required|min:6|same:password_confirmation',
        'password_confirmation' => 'required|min:6',
    ]);

    session([
        'doctor_signup.password' => $request->password,
    ]);

    return redirect()->route('doctor.signup.step3');
})->name('doctor.signup.step2.post');

Route::get('/doctor/signup/step3', function () {
    return view('doctor-signup.step3');
})->name('doctor.signup.step3');

Route::post('/doctor/signup/step3', function (Request $request) {
    $request->validate([
        'phone' => 'required|string|max:30',
        'sex' => 'required|in:Male,Female',
        'specialize' => 'required|string|max:255',
        'dob' => 'required|date',
    ]);

    session([
        'doctor_signup.phone' => $request->phone,
        'doctor_signup.sex' => $request->sex,
        'doctor_signup.specialize' => $request->specialize,
        'doctor_signup.dob' => $request->dob,
    ]);

    return redirect()->route('doctor.signup.complete');
})->name('doctor.signup.step3.post');

Route::get('/doctor/signup/complete', function () {
    return response()->json([
        'message' => 'Doctor signup completed',
        'data' => session('doctor_signup')
    ]);
})->name('doctor.signup.complete');

//login page
Route::get('/login-page', function () {
    return view('login-page');
})->name('login.page');

//home
Route::get('/parents/home', function () {
    return view('parents.home');
})->name('parents.home');

//notifications
Route::get('/parents/alerts', function () {
    return view('parents.alerts');
})->name('parents.alerts');

//reports
Route::get('/parents/reports', function () {
    return 'Reports page';
})->name('parents.reports');


//location
Route::get('/parents/location', function () {
    return view('parents.location');
})->name('parents.location');

//parents doctor
Route::get('/parents/doctors', function () {
    return view('parents.doctors');
})->name('parents.doctors');

//profile
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/edit-profile', function () {
    return view('edit-profile');
})->name('edit.profile');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');
//settings 
Route::get('/password-manager', function () {
    return view('password-manager');
})->name('password.manager');

Route::get('/panic-alert', function () {
    return view('panic-alert');
})->name('panic.alert');

Route::get('/location-alerts', function () {
    return view('location-alerts');
})->name('location.alerts');

Route::get('/safe-zone-settings', function () {
    return view('safe-zone-settings');
})->name('safe.zone.settings');

Route::get('/alert-sounds', function () {
    return view('alert-sounds');
})->name('alert.sounds');

Route::get('/reports-history', function () {
    $reports = [
        ['id' => 1, 'title' => 'Jan Report'],
        ['id' => 2, 'title' => 'Feb Report'],
        ['id' => 3, 'title' => 'Mar Report'],
    ];

    return view('reports-history', compact('reports'));
})->name('reports.history');

Route::get('/reports-history/{id}', function ($id) {
    $reports = [
        1 => ['title' => 'Jan Report'],
        2 => ['title' => 'Feb Report'],
        3 => ['title' => 'Mar Report'],
    ];

    $report = $reports[$id] ?? null;

    if (!$report) {
        abort(404);
    }

    return view('report-details', compact('report'));
})->name('reports.details');

Route::get('/reports-settings', function () {
    return view('reports-settings');
})->name('reports.settings');

Route::delete('/parents/doctors/{id}', [DoctorController::class, 'destroy'])->name('parents.doctors.delete');
//doctor

Route::get('/parents/doctors', function () {
    $doctor = [
        [
            'id' => 1,
            'name' => 'Dr. Alexander Bennett',
            'specialty' => 'Pediatric Neurologist',
            'image' => 'doctor1.png'
        ],
        [
            'id' => 2,
            'name' => 'Dr. Michael Davidson',
            'specialty' => 'Licensed Psychiatrist',
            'image' => 'doctor2.png'
        ],
        [
            'id' => 3,
            'name' => 'Dr. Olivia Turner',
            'specialty' => 'Speech-Language Pathologist',
            'image' => 'doctor3.png'
        ],
        [
            'id' => 4,
            'name' => 'Dr. Sophia Martinez',
            'specialty' => 'ABA Therapist',
            'image' => 'doctor4.png'
        ],
    ];

    return view('parents.doctors', compact('doctor'));
})->name('parents.doctors');



//parents doctor profile
Route::get('/parents/doctor-profile/{id}', function ($id) {
    $doctor = [
        'id' => $id,
        'name' => 'Dr. Alexander Bennett',
        'specialty' => 'Pediatric Neurologist',
        'image' => 'doctor1.png',
    ];

    return view('parents.doctor-profile', compact('doctor'));
})->name('parents.doctor-profile');

//parents chat
Route::get('/parents/chat/{id}', function ($id) {
    $doctor = [
        'id' => $id,
        'name' => 'Dr. Olivia Turner',
        'image' => 'doctor3.png',
    ];
    return view('parents.chat', compact('doctor'));
})->name('parents.chat');


//parents request
Route::get('/parents/request', function () {
    return view('parents.request');
})->name('parents.request');

//doctor home
Route::get('/doctor/home', function () {
    return view('doctor.home');
})->name('doctor.home');

//doctor request
Route::get('/doctor/request', function () {
    return view('doctor.request');
})->name('doctor.request');

//doctor parents
Route::get('/doctor/parents', function () {
    $parents = [
        [
            'id' => 1,
            'name' => 'Ali Salah',
            'subtitle' => "Ahmed Salah's father",
            'image' => 'p1.png',
        ],
        [
            'id' => 2,
            'name' => 'Hifa Jaber',
            'subtitle' => "Wala Ali's mother",
            'image' => 'p2.png',
        ],
        [
            'id' => 3,
            'name' => 'Marwan Hasan',
            'subtitle' => "Maryam Hasan's father",
            'image' => 'p3.png',
        ],
    ];

    return view('doctor.parents', compact('parents'));
})->name('doctor.parents');

//doctor parent profile
Route::get('/doctors/parent-profile/{id}', function ($id) {
    $parent = [
        'id' => $id,
        'name' => 'Ali Saloh',
        'subtitle' => "Ahmed Salah’s father",
        'image' => 'p1.png',
        'phone' => '09X - XXXXXXX',
        'autism_level' => 'Autism Levels: Mild',
    ];

    return view('doctor.parent-profile', compact('parent'));
})->name('doctor.parent.profile');

//doctor chat
Route::get('/doctor/chat/{id}', function ($id) {
    $parent = [
        'id' => $id,
        'name' => 'Ali Salah',
        'image' => 'p1.png',
    ];

    return view('doctor.chat', compact('parent'));
})->name('doctor.chat');

Route::delete('/doctor/parent/{id}', [DoctorController::class, 'deleteParent'])
    ->name('doctor.parent.delete');
    
//doctor appointments
Route::get('/doctor/appointments', function () {
    return view('doctor.Appointments');
})->name('doctor.appointments');

//doctor add appointment
Route::get('/doctor/add-appointment', function () {
    return view('doctor.add-appointment');
})->name('doctor.add.appointment');

Route::post('/doctor/add-appointment', function (Request $request) {
    $request->validate([
        'appointment_date' => 'required|date',
        'from_hour' => 'required',
        'from_minute' => 'required',
        'from_period' => 'required|in:AM,PM',
        'to_hour' => 'required',
        'to_minute' => 'required',
        'to_period' => 'required|in:AM,PM',
        'patient_id' => 'required',
        'clinic_name' => 'required|string|max:255',
        'note' => 'nullable|string|max:1000',
    ]);

    return back()->with('success', 'Appointment added successfully.');
})->name('doctor.add.appointment.store');

//apponintment delete
Route::delete('/doctor/appointments/{id}', function ($id) {
    return back()->with('success', 'Appointment deleted successfully.');
})->name('doctor.appointments.delete'); 

//doctor profile
Route::get('/doctor/doctor-profile', function () {
    return view('doctor.doctor-profile');
})->name('doctor.doctor-profile');

//doctor privacy
Route::get('/doctor/privacy', function () {
    return view('doctor.privacy');
})->name('doctor.privacy');

//doctor settings
Route::get('/doctor/settings', function () {
    return view('doctor.settings');
})->name('doctor.settings');

//doctor edit profile
Route::get('/doctor/edit-profile', function () {
    return view('doctor.edit-profile');
})->name('doctor.edit-profile');

Route::put('/doctor/edit-profile', function (Request $request) {
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',
        'email' => 'required|email|max:255',
        'sex' => 'required|in:Male,Female',
        'specialize' => 'required|string|max:255',
        'birth_day' => 'required',
        'birth_month' => 'required',
        'birth_year' => 'required',
        'bio' => 'nullable|string|max:1000',
    ]);

    return back()->with('success', 'Profile updated successfully');
})->name('doctor.edit-profile.update');

//doctor workplace
Route::get('/doctor/workplace-timing', function () {
    return view('doctor.workplace-timing');
})->name('doctor.workplace.timing');

Route::get('/doctor/workplace/create', function () {
    return 'Create workplace page';
})->name('doctor.workplace.create');

Route::get('/doctor/workplace/edit/{id}', function ($id) {
    return 'Edit workplace page: ' . $id;
})->name('doctor.workplace.edit');

Route::delete('/doctor/workplace/delete/{id}', function ($id) {
    return back()->with('success', 'Workplace deleted');
})->name('doctor.workplace.delete');

//add workplace
Route::get('/doctor/add-workplace', function () {
    return view('doctor.add-workplace');
})->name('doctor.add.workplace');

Route::post('/doctor/add-workplace', function (Request $request) {
    $request->validate([
        'days' => 'required|array|min:1',
        'days.*' => 'in:SAT,SUN,MON,TUE,WED,THU,FRI',
        'from_hour' => 'required',
        'from_minute' => 'required',
        'from_period' => 'required|in:AM,PM',
        'to_hour' => 'required',
        'to_minute' => 'required',
        'to_period' => 'required|in:AM,PM',
        'location' => 'required|string|max:255',
    ]);

    return back()->with('success', 'Workplace added successfully');
})->name('doctor.add.workplace.store');

//doctor edit workplace
Route::get('/doctor/edit-workplace/{id}', function ($id) {
    $workplace = [
        'id' => $id,
        'days' => ['MON', 'WED', 'SAT'],
        'from_hour' => '08',
        'from_minute' => '30',
        'from_period' => 'AM',
        'to_hour' => '10',
        'to_minute' => '00',
        'to_period' => 'AM',
        'location' => 'Tajora',
    ];

    return view('doctor.edit-workplace', compact('workplace'));
})->name('doctor.edit-workplace');

Route::put('/doctor/workplace-update/{id}', function (Request $request, $id) {
    $request->validate([
        'days' => 'required|array|min:1',
        'days.*' => 'in:SAT,SUN,MON,TUE,WED,THU,FRI',
        'from_hour' => 'required',
        'from_minute' => 'required',
        'from_period' => 'required|in:AM,PM',
        'to_hour' => 'required',
        'to_minute' => 'required',
        'to_period' => 'required|in:AM,PM',
        'location' => 'required|string|max:255',
    ]);

    return back()->with('success', 'Workplace updated successfully');
})->name('doctor.workplace.update');

//doctor change password

Route::get('/doctor/change-password', function () {
    return view('doctor.change-password');
})->name('doctor.password');

Route::post('/doctor/change-password', function (Request $request) {

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user(); // أو Doctor::first()

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['Current password is incorrect']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password updated successfully');
})->name('doctor.password.update');

//doctor alert sounds
Route::get('/doctor/alert-sounds', function () {

    // بيانات افتراضية
    $settings = [
        'notif_sound' => true,
        'notif_vibrate' => false,
        'msg_sound' => true,
        'msg_vibrate' => false,
    ];

    return view('doctor.alert-sounds', compact('settings'));
})->name('doctor.alert');

Route::post('/doctor/alert-sounds', function (Request $request) {

    // هنا تخزن في DB لو عندك جدول
    // مثال:
    // auth()->user()->update([...]);

    return back()->with('success', 'Settings updated');
})->name('doctor.alert.update');

//doctor delete account
Route::delete('/doctor/delete-account', function () {
    return back()->with('success', 'Account deleted');
})->name('doctor.delete.account');

//doctor log out
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');



Route::get('/parents/report', [ReportController::class, 'show'])->name('parents.report');
Route::get('/parents/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('parents.report.download-pdf');*/