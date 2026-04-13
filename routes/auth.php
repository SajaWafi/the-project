<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Welcome
Route::get('/', function () {
    return view('welcome-screen');
})->name('welcome');

Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');

// Login
Route::get('/login-page', function () {
    return view('login-page');
})->name('login.page');

// Parent signup
Route::prefix('signup')->name('signup.')->group(function () {
    Route::get('/step1', function () {
        return view('signup.step1');
    })->name('step1');

    Route::post('/step1', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'agree' => 'required',
        ]);

        session([
            'signup.email' => $request->email,
            'signup.first_name' => $request->first_name,
            'signup.last_name' => $request->last_name,
            'signup.phone' => $request->phone,
        ]);

        return redirect()->route('signup.step2');
    })->name('step1.post');

    Route::get('/step2', function () {
        return view('signup.step2');
    })->name('step2');

    Route::post('/step2', function (Request $request) {
        $request->validate([
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);

        session([
            'signup.password' => $request->password,
        ]);

        return redirect()->route('signup.step3');
    })->name('step2.post');

    Route::get('/step3', function () {
        return view('signup.step3');
    })->name('step3');

    Route::post('/step3', function (Request $request) {
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
    })->name('step3.post');

    Route::get('/step4', function () {
        return view('signup.step4');
    })->name('step4');

    Route::post('/step4', function (Request $request) {
        $request->validate([
            'autism_level' => 'required|in:Mild,Moderate,Severe',
        ]);

        session([
            'signup.autism_level' => $request->autism_level,
        ]);

        return redirect()->route('signup.complete');
    })->name('step4.post');

    Route::get('/complete', function () {
        return response()->json([
            'message' => 'Signup completed',
            'data' => session('signup'),
        ]);
    })->name('complete');
});

// Doctor signup
Route::prefix('doctor/signup')->name('doctor.signup.')->group(function () {
    Route::get('/step1', function () {
        return view('doctor-signup.step1');
    })->name('step1');

    Route::post('/step1', function (Request $request) {
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
    })->name('step1.post');

    Route::get('/step2', function () {
        return view('doctor-signup.step2');
    })->name('step2');

    Route::post('/step2', function (Request $request) {
        $request->validate([
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);

        session([
            'doctor_signup.password' => $request->password,
        ]);

        return redirect()->route('doctor.signup.step3');
    })->name('step2.post');

    Route::get('/step3', function () {
        return view('doctor-signup.step3');
    })->name('step3');

    Route::post('/step3', function (Request $request) {
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
    })->name('step3.post');

    Route::get('/complete', function () {
        return response()->json([
            'message' => 'Doctor signup completed',
            'data' => session('doctor_signup'),
        ]);
    })->name('complete');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login.page');
})->name('logout');