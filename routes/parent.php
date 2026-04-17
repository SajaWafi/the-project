<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parent\ProfileController;

Route::prefix('parents')->name('parents.')->group(function () {
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

    Route::get('/doctors', function () {
        $doctors = [
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

        return view('parents.doctors', compact('doctors'));
    })->name('doctors');

    Route::get('/doctor-profile/{id}', function ($id) {
        $doctor = [
            'id' => $id,
            'name' => 'Dr. Alexander Bennett',
            'specialty' => 'Pediatric Neurologist',
            'image' => 'doctor1.png',
        ];

        return view('parents.doctor-profile', compact('doctor'));
    })->name('doctor-profile');

    Route::get('/chat/{id}', function ($id) {
        $doctor = [
            'id' => $id,
            'name' => 'Dr. Olivia Turner',
            'image' => 'doctor3.png',
        ];

        return view('parents.chat', compact('doctor'));
    })->name('chat');

    Route::get('/report', [ReportController::class, 'show'])->name('report');
    Route::get('/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('report.download-pdf');
});

// General parent-related pages outside /parents prefix
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

Route::middleware('auth')->group(function () {
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('parent.profile.edit');
    Route::post('/edit-profile/update', [ProfileController::class, 'update'])->name('parent.profile.update');
});