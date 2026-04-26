<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\DoctorRequest;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\Parent\DoctorController;
use App\Http\Controllers\Parent\ChatController;
use App\Http\Controllers\Parent\AlertController;

/*
|--------------------------------------------------------------------------
| Parent Main Routes
|--------------------------------------------------------------------------
*/
Route::prefix('parents')
    ->name('parents.')
    ->middleware(['auth', 'role:parent'])
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home Page
    |--------------------------------------------------------------------------
    */
    Route::get('/home', function () {

        $user = auth()->user();

        if (!$user || !$user->parentProfile) {
            abort(404, 'Parent profile not found.');
        }

        $parentProfile = $user->parentProfile;
        $child = $parentProfile->children()->first();

        $appointments = collect();

        if ($child) {
            $appointments = \App\Models\Appointment::with(['child', 'doctor.user'])
                ->where('parent_id', $parentProfile->id)
                ->where('child_id', $child->id)
                ->whereDate('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->get();
        }

        return view('parents.home', compact('appointments', 'child'));

    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | Alerts / Location / Requests
    |--------------------------------------------------------------------------
    */
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
    Route::get('/location', fn() => view('parents.location'))->name('location');

    Route::get('/request', function () {
        $parent = auth()->user()->parentProfile;

        if (!$parent) {
            abort(404);
        }

        $requests = DoctorRequest::with('doctor.user')
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('parents.requests', compact('requests'));
    })->name('request');

    /*
    |--------------------------------------------------------------------------
    | Doctor Requests Accept / Reject
    |--------------------------------------------------------------------------
    */
    Route::post('/doctor-requests/{id}/accept', function ($id) {
        $parent = auth()->user()->parentProfile;

        $requestItem = DoctorRequest::where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $child = $parent->children()->first();

        if ($child) {
            DB::table('child_doctor')->insert([
                'child_id' => $child->id,
                'doctor_id' => $requestItem->doctor_id,
            ]);
        }

        $requestItem->update(['status' => 'accepted']);

        return back()->with('success', 'Request accepted');
    })->name('requests.accept');

    Route::post('/doctor-requests/{id}/reject', function ($id) {
        $parent = auth()->user()->parentProfile;

        $requestItem = DoctorRequest::where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $requestItem->update(['status' => 'rejected']);

        return back()->with('success', 'Request rejected');
    })->name('requests.reject');

    /*
    |--------------------------------------------------------------------------
    | Doctors
    |--------------------------------------------------------------------------
    */
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
    Route::delete('/doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete');

    /*
    |--------------------------------------------------------------------------
    | Chat
    |--------------------------------------------------------------------------
    */
    Route::get('/chat/{doctorId}', [ChatController::class, 'show'])->name('chat');
    Route::post('/chat/{doctorId}/send', [ChatController::class, 'send'])->name('chat.send');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/report', [ReportController::class, 'show'])->name('report');
    Route::get('/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('report.download-pdf');

});

/*
|--------------------------------------------------------------------------
| Settings / Profile
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:parent'])->group(function () {

    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/edit-profile', fn() => view('edit-profile'))->name('edit.profile');
    Route::get('/privacy-policy', fn() => view('privacy-policy'))->name('privacy.policy');
    Route::get('/settings', fn() => view('settings'))->name('settings');

    /*
    |--------------------------------------------------------------------------
    | Password Manager
    |--------------------------------------------------------------------------
    */
    Route::get('/password-manager', fn() => view('password-manager'))->name('password.manager');

    Route::post('/password-manager', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Wrong password']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated');
    })->name('password.manager.update');

    /*
    |--------------------------------------------------------------------------
    | Alerts & Safety
    |--------------------------------------------------------------------------
    */
    Route::get('/panic-alert', fn() => view('panic-alert'))->name('panic.alert');
    Route::get('/location-alerts', fn() => view('location-alerts'))->name('location.alerts');
    Route::get('/safe-zone-settings', fn() => view('safe-zone-settings'))->name('safe.zone.settings');
    Route::get('/alert-sounds', fn() => view('alert-sounds'))->name('alert.sounds');

    /*
    |--------------------------------------------------------------------------
    | Reports History
    |--------------------------------------------------------------------------
    */
    Route::get('/reports-history', fn() => view('reports-history'))->name('reports.history');

    /*
    |--------------------------------------------------------------------------
    | Delete Account
    |--------------------------------------------------------------------------
    */
    Route::delete('/delete-account', function () {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            if ($user?->parentProfile) {
                $user->parentProfile()->delete();
            }

            Auth::logout();
            $user?->delete();

            DB::commit();

            return redirect()->route('login.page');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    })->name('delete.account');

});
