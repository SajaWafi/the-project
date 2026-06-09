<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorRequest;

// الكنترولرات الأساسية
use App\Http\Controllers\HomeController as MainHomeController; 
use App\Http\Controllers\ReportController;

// كنترولرات الأهل
use App\Http\Controllers\Parent\DoctorController;
use App\Http\Controllers\Parent\ChatController;
use App\Http\Controllers\Parent\AlertController;
use App\Http\Controllers\Parent\ParentAlertController; 
use App\Http\Controllers\Parent\ParentComplaintController;
use App\Http\Controllers\Parent\HomeController;
use App\Http\Controllers\Parent\LocationController;
use App\Http\Controllers\Parent\SafeZoneController;
use App\Http\Controllers\Parent\BraceletController;
use App\Http\Controllers\Parent\ProfileController; 
use App\Http\Controllers\Parent\ParentRequestController;

// كنترولر الإعدادات الذكي اللي درناه للإشعارات (مشترك)
use App\Http\Controllers\NotificationSettingController;

/*
|--------------------------------------------------------------------------
| Parent Main Routes
|--------------------------------------------------------------------------
*/
Route::prefix('parents')->middleware(['auth', 'role:parent'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home & Live Data
    |--------------------------------------------------------------------------
    */
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/home/live-data', [HomeController::class, 'getLiveData'])->name('home.live');

    /*
    |--------------------------------------------------------------------------
    | Profile & General Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/edit-profile', fn() => view('edit-profile'))->name('edit.profile');
    Route::post('/edit-profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', fn() => view('settings'))->name('settings');
    Route::get('/privacy-policy', fn() => view('privacy-policy'))->name('privacy.policy');

    /*
    |--------------------------------------------------------------------------
    | Notification Settings (AJAX Dynamic Toggle)
    |--------------------------------------------------------------------------
    */
    // الراوت هذا هو اللي حيستقبل كل تعديلات الأزرار ويخزنها في الداتابيز
    Route::post('/settings/toggle', [NotificationSettingController::class, 'toggleSetting'])->name('settings.toggle');

    /*
    |--------------------------------------------------------------------------
    | Password Manager
    |--------------------------------------------------------------------------
    */
    Route::get('/password-manager', fn() => view('password-manager'))->name('password.manager');
    // استخدمت الكنترولر هنا باش يكون الكود أنظف (تأكدي إن دالة updatePassword موجودة في ProfileController)
    Route::post('/password-manager', [ProfileController::class, 'updatePassword'])->name('password.manager.update');

    /*
    |--------------------------------------------------------------------------
    | Account Deletion
    |--------------------------------------------------------------------------
    */
    // استخدمت الكنترولر للحذف باش ملف الراوتات يقعد Clean
    Route::delete('/delete-account', [ProfileController::class, 'destroyAccount'])->name('delete.account');

    /*
    |--------------------------------------------------------------------------
    | Smart Bracelet
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/bracelet', [BraceletController::class, 'showConnectBracelet'])->name('bracelet.show');
    Route::post('/profile/bracelet/connect', [BraceletController::class, 'connectBracelet'])->name('bracelet.connect');
    Route::post('/profile/bracelet/disconnect', [BraceletController::class, 'disconnectBracelet'])->name('bracelet.disconnect');

    /*
    |--------------------------------------------------------------------------
    | Alerts & Location & Safe Zone
    |--------------------------------------------------------------------------
    */
    Route::get('/alerts', [ParentAlertController::class, 'index'])->name('alerts');
    Route::post('/alerts/{id}/response', [ParentAlertController::class, 'updateResponse'])->name('alerts.response');
    
    Route::get('/location', [LocationController::class, 'index'])->name('location');
    Route::get('/location/live', [LocationController::class, 'getLiveLocation'])->name('location.live');

    Route::get('/safe-zone-settings', [SafeZoneController::class, 'index'])->name('safe.zone.settings');
    Route::post('/safe-zone-settings', [SafeZoneController::class, 'store'])->name('safe.zone.store');
    Route::delete('/safe-zone-settings/{id}', [SafeZoneController::class, 'destroy'])->name('safe.zone.destroy');

    // صفحات إعدادات التنبيهات (الواجهات)
    Route::get('/panic-alert', function () {
    $userSettings = \App\Models\NotificationSetting::where('user_id', auth()->id())->get()->keyBy('notification_type');
    return view('panic-alert', compact('userSettings'));
})->name('panic.alert');
    Route::get('/location-alerts', fn() => view('location-alerts'))->name('location.alerts');
    Route::get('/alert-sounds', fn() => view('alert-sounds'))->name('alert.sounds');

    /*
    |--------------------------------------------------------------------------
    | Doctor Requests
    |--------------------------------------------------------------------------
    */
    Route::get('/requests', [ParentRequestController::class, 'index'])->name('requests');
    // الكود اللي كتبتيه لقبول ورفض الطلبات خليته زي ما هو لأنه يخدم بالـ Closures
    Route::post('/doctor-requests/{id}/accept', function ($id) {
        $parent = auth()->user()->parentProfile;
        if (!$parent) return back()->withErrors(['parent' => 'Parent profile not found.']);

        $requestItem = DoctorRequest::where('parent_id', $parent->id)->where('status', 'pending')->findOrFail($id);
        $child = $parent->children()->first();
        if (!$child) return back()->withErrors(['child' => 'No child found for this parent.']);

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
        $requestItem->update(['status' => 'accepted']);
        return back()->with('success', 'Request accepted successfully.');
    })->name('requests.accept');

    Route::post('/doctor-requests/{id}/reject', function ($id) {
        $parent = auth()->user()->parentProfile;
        if (!$parent) return back()->withErrors(['parent' => 'Parent profile not found.']);

        $requestItem = DoctorRequest::where('parent_id', $parent->id)->where('status', 'pending')->findOrFail($id);
        $requestItem->update(['status' => 'rejected']);
        return back()->with('success', 'Request rejected.');
    })->name('requests.reject');

    /*
    |--------------------------------------------------------------------------
    | Doctors Management & Chat
    |--------------------------------------------------------------------------
    */
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/doctor-profile/{id}', [DoctorController::class, 'show'])->name('doctor-profile');
    Route::delete('/doctors/{id}', [DoctorController::class, 'removeDoctor'])->name('doctors.delete');

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
    Route::get('/settings/reports-config', [ReportController::class, 'settings'])->name('reports.settings');
    Route::get('/settings/reports-history', [ReportController::class, 'history'])->name('reports.history');
    Route::post('/settings/reports-history/delete', [ReportController::class, 'destroyMultiple'])->name('reports.destroy');

    /*
    |--------------------------------------------------------------------------
    | Complaints
    |--------------------------------------------------------------------------
    */
    Route::get('/complaint', [ParentComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaint', [ParentComplaintController::class, 'store'])->name('complaints.store');

});

/*
|--------------------------------------------------------------------------
| Shared Routes (FCM)
|--------------------------------------------------------------------------
*/
Route::post('/save-fcm-token', [MainHomeController::class, 'saveToken'])->name('save.token');
