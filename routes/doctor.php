<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Workplace;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;

use App\Http\Controllers\Doctor\ParentController;
use App\Http\Controllers\Doctor\ChatController;
use App\Http\Controllers\Doctor\ChildController;

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

    Route::get('/settings', function () {
        return view('doctor.settings');
    })->name('settings');

    Route::get('/doctor-profile', function () {
        return view('doctor.doctor-profile');
    })->name('doctor-profile');

    Route::get('/privacy', function () {
        return view('doctor.privacy');
    })->name('privacy');

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
        $user = auth()->user()->load('doctorProfile');
        return view('doctor.edit-profile', compact('user'));
    })->name('edit-profile');

    Route::put('/edit-profile', function (Request $request) {
        $request->validate([
            'full_name'     => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:30',
            'email'         => 'nullable|email|max:255|unique:users,email,' . auth()->id(),
            'gender'        => 'nullable|in:Male,Female',
            'specialize'    => 'nullable|string|max:255',
            'birth_day'     => 'nullable',
            'birth_month'   => 'nullable',
            'birth_year'    => 'nullable',
            'bio'           => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $userData = [];

            if ($request->filled('full_name')) {
                $parts = explode(' ', trim($request->full_name), 2);
                $userData['first_name'] = $parts[0] ?? '';
                $userData['last_name'] = $parts[1] ?? '';
            }

            if ($request->filled('phone')) {
                $userData['phone'] = $request->phone;
            }

            if ($request->filled('email')) {
                $userData['email'] = $request->email;
            }

            if ($request->filled('gender')) {
                $userData['gender'] = $request->gender;
            }

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $userData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
            }

            if (!empty($userData)) {
                $user->update($userData);
            }

            $doctorData = [];

            if ($request->filled('specialize')) {
                $doctorData['specialization'] = $request->specialize;
            }

            if ($request->filled('bio')) {
                $doctorData['bio'] = $request->bio;
            }

            if ($request->filled('birth_day') && $request->filled('birth_month') && $request->filled('birth_year')) {
                $doctorData['birth_date'] = $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day;
            }

            if (!empty($doctorData)) {
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $doctorData
                );
            }

            DB::commit();

            return back()->with('success', 'Profile updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    })->name('edit-profile.update');

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
        $user = Auth::user();

        DB::beginTransaction();

        try {
            if ($user?->doctorProfile) {
                $user->doctorProfile()->delete();
            }

            Auth::logout();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect('/')->with('success', 'Account deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    })->name('delete.account');

    /*
    |--------------------------------------------------------------------------
    | Children Search / Attach
    |--------------------------------------------------------------------------
    */

    Route::get('/children/search', [ChildController::class, 'searchPage'])->name('children.search');
    Route::get('/children/find', [ChildController::class, 'find'])->name('children.find');
    Route::post('/children/{id}/attach', [ChildController::class, 'attach'])->name('children.attach');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.page');
})->name('logout');
});