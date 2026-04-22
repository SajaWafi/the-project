<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\DoctorRequest;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\Parent\ProfileController;
use App\Http\Controllers\Parent\DoctorController;
use App\Http\Controllers\Parent\ChatController;

Route::prefix('parents')->name('parents.')->middleware(['auth', 'role:parent'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Main Pages
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

    Route::get('/alerts', function () {
        return view('parents.alerts');
    })->name('alerts');

    Route::get('/location', function () {
        return view('parents.location');
    })->name('location');

    Route::get('/report', [ReportController::class, 'show'])->name('report');
    Route::get('/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('report.download-pdf');

    /*
    |--------------------------------------------------------------------------
    | Requests
    |--------------------------------------------------------------------------
    */

    Route::get('/request', function () {
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
                'child' => 'No child found for this parent.'
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
    | Doctors
    |--------------------------------------------------------------------------
    */

    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
    Route::delete('/doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete');

    Route::get('/chat/{doctorId}', [ChatController::class, 'show'])->name('chat');
    Route::post('/chat/{doctorId}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/message/{messageId}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');

});

    /*
    |--------------------------------------------------------------------------
    | Edit Profile
    |--------------------------------------------------------------------------
    */

Route::middleware(['auth', 'role:parent'])->group(function () {

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

    Route::get('/password-manager', function () {
        return view('password-manager');
    })->name('password.manager');

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

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    })->name('password.manager.update');

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

        return view('report-details', compact('report', 'id'));
    })->name('reports.history.details');

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
                $children = $user->parentProfile->children();

                if ($children) {
                    $children->delete();
                }

                $user->parentProfile()->delete();
            }

            Auth::logout();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect('/login-page')->with('success', 'Account deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    })->name('delete.account');
});