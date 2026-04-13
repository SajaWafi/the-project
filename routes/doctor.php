<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/home', function () {
        return view('doctor.home');
    })->name('home');

    Route::get('/request', function () {
        return view('doctor.request');
    })->name('request');

    Route::get('/parents', function () {
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
    })->name('parents');

    Route::get('/parent-profile/{id}', function ($id) {
        $parent = [
            'id' => $id,
            'name' => 'Ali Saloh',
            'subtitle' => "Ahmed Salah’s father",
            'image' => 'p1.png',
            'phone' => '09X - XXXXXXX',
            'autism_level' => 'Autism Levels: Mild',
        ];

        return view('doctor.parent-profile', compact('parent'));
    })->name('parent.profile');

    Route::get('/chat/{id}', function ($id) {
        $parent = [
            'id' => $id,
            'name' => 'Ali Salah',
            'image' => 'p1.png',
        ];

        return view('doctor.chat', compact('parent'));
    })->name('chat');

    Route::get('/appointments', function () {
        return view('doctor.Appointments');
    })->name('appointments');

    Route::get('/add-appointment', function () {
        return view('doctor.add-appointment');
    })->name('add.appointment');

    Route::post('/add-appointment', function (Request $request) {
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
    })->name('add.appointment.store');

    Route::delete('/appointments/{id}', function ($id) {
        return back()->with('success', 'Appointment deleted successfully.');
    })->name('appointments.delete');

    Route::get('/doctor-profile', function () {
        return view('doctor.doctor-profile');
    })->name('doctor-profile');

    Route::get('/privacy', function () {
        return view('doctor.privacy');
    })->name('privacy');

    Route::get('/settings', function () {
        return view('doctor.settings');
    })->name('settings');

    Route::get('/edit-profile', function () {
        return view('doctor.edit-profile');
    })->name('edit-profile');

    Route::put('/edit-profile', function (Request $request) {
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
    })->name('edit-profile.update');

    Route::get('/workplace-timing', function () {
        return view('doctor.workplace-timing');
    })->name('workplace.timing');

    Route::get('/workplace/create', function () {
        return 'Create workplace page';
    })->name('workplace.create');

    Route::get('/workplace/edit/{id}', function ($id) {
        return 'Edit workplace page: ' . $id;
    })->name('workplace.edit');

    Route::delete('/workplace/delete/{id}', function ($id) {
        return back()->with('success', 'Workplace deleted');
    })->name('workplace.delete');

    Route::get('/add-workplace', function () {
        return view('doctor.add-workplace');
    })->name('add.workplace');

    Route::post('/add-workplace', function (Request $request) {
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
    })->name('add.workplace.store');

    Route::get('/edit-workplace/{id}', function ($id) {
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
    })->name('edit-workplace');

    Route::put('/workplace-update/{id}', function (Request $request, $id) {
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
    })->name('workplace.update');

    Route::get('/change-password', function () {
        return view('doctor.change-password');
    })->name('password');

    Route::post('/change-password', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    })->name('password.update');

    Route::get('/alert-sounds', function () {
        $settings = [
            'notif_sound' => true,
            'notif_vibrate' => false,
            'msg_sound' => true,
            'msg_vibrate' => false,
        ];

        return view('doctor.alert-sounds', compact('settings'));
    })->name('alert');

    Route::post('/alert-sounds', function (Request $request) {
        return back()->with('success', 'Settings updated');
    })->name('alert.update');

    Route::delete('/delete-account', function () {
        return back()->with('success', 'Account deleted');
    })->name('delete.account');
});