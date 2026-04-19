<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parent\ProfileController;
use App\Http\Controllers\Parent\DoctorController;

Route::prefix('parents')->name('parents.')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/home', function () {
        return view('parents.home');
    })->name('home');

    Route::get('/alerts', function () {
        return view('parents.alerts');
    })->name('alerts');

    Route::get('/location', function () {
        return view('parents.location');
    })->name('location');

    Route::get('/request', function () {
        return view('parents.request');
    })->name('request');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
Route::get('/chat/{id}', [DoctorController::class, 'chat'])->name('chat');

    Route::get('/report', [ReportController::class, 'show'])->name('report');
    Route::get('/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('report.download-pdf');
});

// General parent-related pages outside /parents prefix
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

<<<<<<< HEAD
    Route::get('/password-manager', function () {
        return view('password-manager');
    })->name('password.manager');

    Route::get('/panic-alert', function () {
        return view('panic-alert');
    })->name('panic.alert');
=======

Route::get('/password-manager', function () {
    return view('password-manager');
})->name('password.manager');

Route::post('/password-manager', function (Illuminate\Http\Request $request) {
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    if (!$user || !Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'Current password is incorrect'
        ]);
    }

    $user->password = Illuminate\Support\Facades\Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password updated successfully');
})->name('password.manager.update');

Route::get('/panic-alert', function () {
    return view('panic-alert');
})->name('panic.alert');
>>>>>>> 32430d76775c2256dea2acdf9252796e2db0ae09

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

<<<<<<< HEAD
    Route::get('/parent/edit-profile', [ProfileController::class, 'edit'])->name('parent.profile.edit');
    Route::post('/parent/edit-profile/update', [ProfileController::class, 'update'])->name('parent.profile.update');
});
=======
Route::middleware('auth')->group(function () {
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('parent.profile.edit');
    Route::post('/edit-profile/update', [ProfileController::class, 'update'])->name('parent.profile.update');
});

Route::delete('/delete-account', function () {
    $user = Auth::user();

    DB::beginTransaction();

    try {
        if ($user?->parentProfile?->child) {
            $user->parentProfile->child()->delete();
        }

        if ($user?->parentProfile) {
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
>>>>>>> 32430d76775c2256dea2acdf9252796e2db0ae09
