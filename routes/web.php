<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::get('/', function () {
  //  return view('welcome');
//});

//welcome screen
Route::get('/', function () {
    return view('welcome-screen');
});
Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');

//login page
Route::get('/login-page', function () {
    return view('login-page');
})->name('login.page');

//signup
Route::get('/signup/step1', function () {
    return view('signup.step1');
})->name('signup.step1');

Route::post('/signup/step1', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'full_name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',
        'agree' => 'required',
    ]);

    session([
        'signup.email' => $request->email,
        'signup.full_name' => $request->full_name,
        'signup.phone' => $request->phone,
    ]);

    return redirect()->route('signup.step2');
})->name('signup.step1.post');

Route::get('/signup/step2', function () {
    return view('signup.step2');
})->name('signup.step2');

Route::post('/signup/step2', function (Request $request) {
    $request->validate([
        'password' => 'required|min:6|same:password_confirmation',
        'password_confirmation' => 'required|min:6',
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
        'sex' => 'required|string|max:50',
        'dob' => 'required|date',
    ]);

    session([
        'signup.child_name' => $request->child_name,
        'signup.sex' => $request->sex,
        'signup.dob' => $request->dob,
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

    return redirect()->route('signup.complete');
})->name('signup.step4.post');

Route::get('/signup/complete', function () {
    return response()->json([
        'message' => 'Signup completed',
        'data' => session('signup')
    ]);
})->name('signup.complete');



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
Route::get('/parents/notifications', function () {
    return view('parents.notifications');
})->name('parents.notifications');

//reports
Route::get('/parents/reports', function () {
    return 'Reports page';
})->name('parents.reports');


//location
Route::get('/paerents/location', function () {
    return view('parents.location');
})->name('parents.location');

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


Route::get('/doctor/profile/{id}', function ($id) {
    return "Doctor Profile ID: " . $id;
})->name('doctor.profile');

Route::get('/doctor/chat/{id}', function ($id) {
    return "Chat with Doctor ID: " . $id;
})->name('doctor.chat');
