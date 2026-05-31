<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController as MainHomeController; 
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Parent\DoctorController;
use App\Http\Controllers\Parent\ChatController;
use App\Http\Controllers\Parent\AlertController;
use App\Http\Controllers\Parent\ParentAlertController; 
use App\Http\Controllers\Parent\ParentComplaintController;
use App\Http\Controllers\Parent\HomeController;
use App\Http\Controllers\Parent\LocationController;
use App\Http\Controllers\Parent\SafeZoneController;
use App\Http\Controllers\Parent\DoctorRequestController; 
use App\Http\Controllers\Parent\ProfileController; 
use App\Http\Controllers\Parent\ParentRequestController;

/*
|--------------------------------------------------------------------------
| Parent Main Routes
|--------------------------------------------------------------------------
*/
Route::prefix('parents')
    ->name('parents.')
    ->middleware(['auth', 'role:parent'])
    ->group(function () {

        // Home Page
        Route::get('/home', [HomeController::class, 'home'])->name('home');
        Route::get('/home/live-data', [HomeController::class, 'getLiveData'])->name('home.live');

        // Alerts / Location
        // ملاحظة: اختر واحداً فقط من الكنترولرات لتجنب التعارض! سأترك ParentAlertController كمثال
        Route::get('/alerts', [ParentAlertController::class, 'index'])->name('alerts');
        Route::post('/alerts/{id}/response', [ParentAlertController::class, 'updateResponse'])->name('alerts.response');
        
        Route::get('/location', [LocationController::class, 'index'])->name('location');
        Route::get('/location/live', [LocationController::class, 'getLiveLocation'])->name('location.live');

        // Doctor Requests
        Route::get('/requests', [ParentRequestController::class, 'index'])->name('requests');
        Route::post('/doctor-requests/{id}/accept', [ParentRequestController::class, 'accept'])->name('requests.accept');
        Route::post('/doctor-requests/{id}/reject', [ParentRequestController::class, 'reject'])->name('requests.reject');

        // Doctors Management
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
        Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
        Route::delete('/doctors/{id}', [DoctorController::class, 'removeDoctor'])->name('doctors.delete');

        // Chat System
        Route::get('/chat/{doctorId}', [ChatController::class, 'show'])->name('chat');
        Route::post('/chat/{doctorId}/send', [ChatController::class, 'send'])->name('chat.send');
        Route::post('/chat/{doctorId}/send-audio', [ChatController::class, 'sendAudio'])->name('chat.sendAudio');
        Route::delete('/chat/message/{messageId}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');

        // Reports
        Route::get('/report', [ReportController::class, 'show'])->name('report');
        Route::get('/report/download-pdf', [ReportController::class, 'downloadPdf'])->name('report.download-pdf');
    });

/*
|--------------------------------------------------------------------------
| Shared Settings / Profile (Common for Parents)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:parent'])->group(function () {

        // Profile & Settings Views
        Route::get('/profile', fn() => view('profile'))->name('profile');
        Route::get('/edit-profile', fn() => view('edit-profile'))->name('edit.profile');
        Route::get('/privacy-policy', fn() => view('privacy-policy'))->name('privacy.policy');
        Route::get('/settings', fn() => view('settings'))->name('settings');

        // Profile Logic (يجب إنشاء ProfileController)
        Route::post('/edit-profile/update', [ProfileController::class, 'updateProfile'])->name('parent.profile.update');
        Route::delete('/delete-account', [ProfileController::class, 'destroyAccount'])->name('delete.account');

        // Password Manager
        Route::get('/password-manager', fn() => view('password-manager'))->name('password.manager');
        Route::post('/password-manager', [ProfileController::class, 'updatePassword'])->name('password.manager.update');

        // Safety Alerts
        Route::get('/panic-alert', fn() => view('panic-alert'))->name('panic.alert');
        Route::get('/location-alerts', fn() => view('location-alerts'))->name('location.alerts');
        Route::get('/safe-zone-settings', fn() => view('safe-zone-settings'))->name('safe.zone.settings');
        Route::get('/alert-sounds', fn() => view('alert-sounds'))->name('alert.sounds');

        // Reports Settings & History
        Route::get('/settings/reports-config', [ReportController::class, 'settings'])->name('reports.settings');
        Route::get('/settings/reports-history', [ReportController::class, 'history'])->name('reports.history');
        Route::post('/settings/reports-history/delete', [ReportController::class, 'destroyMultiple'])->name('reports.destroy');

});

/*
|--------------------------------------------------------------------------
| Safe Zone
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/safe-zone-settings', [SafeZoneController::class, 'index'])->name('safe.zone.settings');
    Route::post('/safe-zone-settings', [SafeZoneController::class, 'store'])->name('safe.zone.store');
    Route::delete('/safe-zone-settings/{id}', [SafeZoneController::class, 'destroy'])->name('safe.zone.destroy');
});

/*
|--------------------------------------------------------------------------
| Complaints
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/complaint', [ParentComplaintController::class, 'create'])->name('parent.complaints.create');
    Route::post('/complaint', [ParentComplaintController::class, 'store'])->name('parent.complaints.store');
});

/*
|--------------------------------------------------------------------------
| FCM Token
|--------------------------------------------------------------------------
*/
Route::post('/save-fcm-token', [MainHomeController::class, 'saveToken'])->name('save.token');