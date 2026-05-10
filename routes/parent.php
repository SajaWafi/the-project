<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\DoctorRequest;
use App\Models\Alert;

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
                    ->orderByRaw("
                        CASE
                            WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                            WHEN from_period = 'AM' THEN from_hour
                            WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                            ELSE from_hour + 12
                        END
                    ")
                    ->orderBy('from_minute')
                    ->get();
            }

            return view('parents.home', compact('appointments', 'child'));
        })->name('home');

        /*
        |--------------------------------------------------------------------------
        | Alerts / Location
        |--------------------------------------------------------------------------
        */
        // ملاحظة: تم توحيد مسار التنبيهات ليعمل من خلال الـ Controller أو الوظيفة المطلوبة
        Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
        Route::get('/location', fn() => view('parents.location'))->name('location');

        /*
        |--------------------------------------------------------------------------
        | Doctor Requests (Pending)
        |--------------------------------------------------------------------------
        */
        Route::get('/requests', function () {
            $parent = auth()->user()->parentProfile;

            if (!$parent) {
                abort(404, 'Parent profile not found.');
            }

            $requests = DoctorRequest::with('doctor.user')
                ->where('parent_id', $parent->id)
                ->where('status', 'pending')
                ->latest()
                ->get();

            return view('parents.requests', compact('requests'));
        })->name('requests');

        /*
        |--------------------------------------------------------------------------
        | Doctor Requests Accept / Reject
        |--------------------------------------------------------------------------
        */
        Route::post('/doctor-requests/{id}/accept', function ($id) {
            $parent = auth()->user()->parentProfile;

            if (!$parent) {
                return back()->withErrors(['parent' => 'Parent profile not found.']);
            }

            $requestItem = DoctorRequest::where('parent_id', $parent->id)
                ->where('status', 'pending')
                ->findOrFail($id);

            $child = $parent->children()->first();

            if (!$child) {
                return back()->withErrors([
                    'child' => 'No child found for this parent.',
                ]);
            }

            $alreadyLinked = DB::table('child_doctor')
                ->where('child_id', $child->id)
                ->where('doctor_id', $requestItem->doctor_id)
                ->exists();

            if (!$alreadyLinked) {
                DB::table('child_doctor')->insert([
                    'child_id' => $child->id,
                    'doctor_id' => $requestItem->doctor_id,
                ]);
            }

            $requestItem->update([
                'status' => 'accepted',
            ]);

            return back()->with('success', 'Request accepted successfully.');
        })->name('requests.accept');

        Route::post('/doctor-requests/{id}/reject', function ($id) {
            $parent = auth()->user()->parentProfile;

            if (!$parent) {
                return back()->withErrors(['parent' => 'Parent profile not found.']);
            }

            $requestItem = DoctorRequest::where('parent_id', $parent->id)
                ->where('status', 'pending')
                ->findOrFail($id);

            $requestItem->update([
                'status' => 'rejected',
            ]);

            return back()->with('success', 'Request rejected.');
        })->name('requests.reject');

        /*
        |--------------------------------------------------------------------------
        | Doctors Management
        |--------------------------------------------------------------------------
        */
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
        Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
        Route::delete('/doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete');

        /*
        |--------------------------------------------------------------------------
        | Chat System
        |--------------------------------------------------------------------------
        */
        Route::get('/chat/{doctorId}', [ChatController::class, 'show'])->name('chat');
        Route::post('/chat/{doctorId}/send', [ChatController::class, 'send'])->name('chat.send');
        Route::post('/chat/{doctorId}/send-audio', [ChatController::class, 'sendAudio'])->name('chat.sendAudio');
        Route::delete('/chat/message/{messageId}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');

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
| Shared Settings / Profile (Common for Parents)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:parent'])->group(function () {

    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/edit-profile', fn() => view('edit-profile'))->name('edit.profile');

    Route::post('/edit-profile/update', function (Request $request) {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();
        $userData = [];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $userData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        return back()->with('success', 'Profile updated successfully');
    })->name('parent.profile.update');

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

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully');
    })->name('password.manager.update');

    /*
    |--------------------------------------------------------------------------
    | Safety Alerts
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
        if (!$report) abort(404);
        return view('report-details', compact('report', 'id'));
    })->name('reports.details');

    Route::get('/reports-settings', fn() => view('reports-settings'))->name('reports.settings');

    /*
    |--------------------------------------------------------------------------
    | Delete Account Logic
    |--------------------------------------------------------------------------
    */
    Route::delete('/delete-account', function () {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            if ($user?->parentProfile) {
                // حذف الأطفال أولاً لتجنب مشاكل Foreign Key
                $user->parentProfile->children()->delete();
                $user->parentProfile()->delete();
            }
            
            $tempUser = $user;
            Auth::logout();
            
            if ($tempUser) {
                $tempUser->delete();
            }

            DB::commit();
            return redirect()->route('login.page')->with('success', 'Account deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while deleting the account.');
        }
    })->name('delete.account');
});