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
use App\Http\Controllers\Parent\BraceletController;
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


        /*
        |--------------------------------------------------------------------------
        | Doctor Requests (Pending)
        |--------------------------------------------------------------------------
        */
      Route::get('/requests', [\App\Http\Controllers\Parent\ParentRequestController::class, 'index'])->name('requests');

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


   Route::post('/edit-profile/update', [\App\Http\Controllers\Parent\ProfileController::class, 'update'])->name('parent.profile.update');

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
    
    Route::post('/alerts/{id}/response', [\App\Http\Controllers\Parent\AlertController::class, 'updateResponse'])->name('parents.alerts.response');


    /*
    |--------------------------------------------------------------------------
    | Reports Settings & History
    |--------------------------------------------------------------------------
    */
    // 1. المسار الناقص اللي كان مسبب الخطأ
    Route::get('/settings/reports-config', function () {
        return "صفحة إعدادات التقارير (قيد الإنشاء 🚧)";
    })->name('reports.settings');

    // 2. مسارات الأرشيف والحذف
    Route::get('/settings/reports-history', [\App\Http\Controllers\ReportController::class, 'history'])->name('reports.history');
    Route::post('/settings/reports-history/delete', [\App\Http\Controllers\ReportController::class, 'destroyMultiple'])->name('reports.destroy');

    /*
    |--------------------------------------------------------------------------
    | Delete Doctor
    |--------------------------------------------------------------------------
    */
    Route::delete('/doctors/{id}/delete', [\App\Http\Controllers\Parent\DoctorController::class, 'removeDoctor'])
    ->name('doctors.delete');

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
    |       complaints
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])->group(function () {

    // رابط عرض صفحة الشكوى
    Route::get('/complaint', [ParentComplaintController::class, 'create'])->name('parent.complaints.create');

    // رابط إرسال وحفظ الشكوى في قاعدة البيانات
    Route::post('/complaint', [ParentComplaintController::class, 'store'])->name('parent.complaints.store');


Route::get('/profile/bracelet', [BraceletController::class, 'showConnectBracelet'])->name('parents.bracelet.show');
Route::post('/profile/bracelet/connect', [BraceletController::class, 'connectBracelet'])->name('parents.bracelet.connect');
Route::post('/profile/bracelet/disconnect', [BraceletController::class, 'disconnectBracelet'])->name('parents.bracelet.disconnect');

    });

    /*
    |--------------------------------------------------------------------------
    |       Alerts
    |--------------------------------------------------------------------------
    */

   //oute::get('/alerts', [ParentAlertController::class, 'index'])->name('parents.alerts');
    /*
    |--------------------------------------------------------------------------
    |       FCM Token
    |--------------------------------------------------------------------------
    */
   Route::post('/save-fcm-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save.token');
   

/*
|--------------------------------------------------------------------------
| FCM Token
|--------------------------------------------------------------------------
*/
Route::post('/save-fcm-token', [MainHomeController::class, 'saveToken'])->name('save.token');

