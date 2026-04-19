<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Workplace;
use App\Models\DoctorProfile;

use App\Http\Controllers\Doctor\ChatController;
use App\Http\Controllers\Doctor\ChildController;
use App\Http\Controllers\Doctor\ParentController;

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home / Main Pages
    |--------------------------------------------------------------------------
    */

    Route::get('/home', function () {
        return view('doctor.home');
    })->name('home');

    Route::get('/request', function () {
        return view('doctor.request');
    })->name('request');

    Route::get('/doctor/settings', function () {
        return view('doctor.settings');
    })->name('doctor.settings');

    Route::get('/doctor-profile', function () {
        return view('doctor.doctor-profile');
    })->name('doctor-profile');

    Route::get('/privacy', function () {
        return view('doctor.privacy');
    })->name('privacy');

    Route::get('/settings', function () {
        return view('doctor.settings');
    })->name('settings');


    /*
    |--------------------------------------------------------------------------
    | Parents
    |--------------------------------------------------------------------------
    */

    Route::get('/parents', [ParentController::class, 'index'])->name('parents');
    Route::get('/parent-profile/{id}', [ParentController::class, 'show'])->name('parent.profile');
    Route::get('/parents/search/ajax', [ParentController::class, 'searchAjax'])->name('parents.search.ajax');


    /*
    |--------------------------------------------------------------------------
    | Chat
    |--------------------------------------------------------------------------
    */

    Route::get('/chat/{parentId}', [ChatController::class, 'show'])->name('chat');
    Route::get('/chat/{parentId}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{parentId}/send', [ChatController::class, 'send'])->name('chat.send');


    /*
    |--------------------------------------------------------------------------
    | Appointments
    |--------------------------------------------------------------------------
    */

    Route::get('/appointments', function () {
        return view('doctor.Appointments');
    })->name('appointments');

    Route::get('/add-appointment', function () {
        $parents = ParentProfile::with('user', 'children')->get();

        return view('doctor.add-appointment', compact('parents'));
    })->name('add.appointment');

    Route::post('/add-appointment', function (Request $request) {
        $request->validate([
            'parent_id' => 'required|exists:parent_profiles,id',
            'child_id' => 'required|exists:children,id',
            'date' => 'required|date',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',
            'note' => 'nullable|string|max:1000',
        ]);

        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors([
                'doctor' => 'Doctor profile not found.'
            ]);
        }

        $child = \App\Models\Child::where('id', $request->child_id)
            ->where('parent_id', $request->parent_id)
            ->first();

        if (!$child) {
            return back()->withErrors([
                'child_id' => 'Selected child does not belong to the selected parent.'
            ])->withInput();
        }

        \App\Models\Appointment::create([
            'doctor_id' => $doctorProfile->id,
            'parent_id' => $request->parent_id,
            'child_id' => $request->child_id,
            'date' => $request->date,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment added successfully.');
    })->name('add.appointment.store');

    Route::delete('/appointments/{id}', function ($id) {
        return back()->with('success', 'Appointment deleted successfully.');
    })->name('appointments.delete');


    /*
    |--------------------------------------------------------------------------
    | Edit Profile
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Workplace Timing
    |--------------------------------------------------------------------------
    */

    Route::get('/workplace-timing', function () {
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)
            ->latest()
            ->get();

        return view('doctor.workplace-timing', compact('workplaces'));
    })->name('workplace.timing');

    Route::get('/workplace/create', function () {
        return 'Create workplace page';
    })->name('workplace.create');

    Route::get('/workplace/edit/{id}', function ($id) {
        return 'Edit workplace page: ' . $id;
    })->name('workplace.edit');

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
            'place_name' => 'required|string|max:255',
        ]);

        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors([
                'doctor' => 'Doctor profile not found.',
            ]);
        }

        Workplace::create([
            'doctor_id' => $doctorProfile->id,
            'place_name' => $request->place_name,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'days' => $request->days,
        ]);

        return back()->with('success', 'Workplace added successfully');
    })->name('add.workplace.store');

    Route::get('/edit-workplace/{id}', function ($id) {
        $doctorProfile = auth()->user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)
            ->findOrFail($id);

        return view('doctor.edit-workplace', compact('workplace'));
    })->name('edit-workplace');

    Route::put('/workplace-update/{id}', function (Request $request, $id) {
        $doctorProfile = auth()->user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)->findOrFail($id);

        $workplace->update([
            'place_name' => $request->place_name,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
        ]);

        return back()->with('success', 'Workplace updated');
    })->name('workplace.update');

    Route::delete('/workplace/delete/{id}', function ($id) {
        $doctorProfile = auth()->user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $workplace->delete();

        return back()->with('success', 'Workplace deleted');
    })->name('workplace.delete');


    /*
    |--------------------------------------------------------------------------
    | Duplicate Workplace Routes (kept as-is)
    |--------------------------------------------------------------------------

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
            'place_name' => 'Tajora',
        ];

        return view('doctor.edit-workplace', compact('workplace'));
    })->name('edit-workplace');

    */

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


    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

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
            return back()->withErrors([
                'current_password' => 'Current password is incorrect'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    })->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | Alert Sounds
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Delete Account
    |--------------------------------------------------------------------------
    */

    Route::delete('/delete-account', function () {
        return back()->with('success', 'Account deleted');
    })->name('delete.account');


    /*
    |--------------------------------------------------------------------------
    | Children Search / Attach
    |--------------------------------------------------------------------------
    */

    Route::get('/children/search', [ChildController::class, 'searchPage'])->name('children.search');
    Route::get('/children/find', [ChildController::class, 'find'])->name('children.find');
    Route::post('/children/{id}/attach', [ChildController::class, 'attach'])->name('children.attach');
});