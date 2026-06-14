<?php

use Illuminate\Support\Facades\Route;

// استدعاء الكونترولرات
use App\Http\Controllers\Doctor\HomeController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\WorkplaceController;
use App\Http\Controllers\Doctor\DoctorRequestController;
use App\Http\Controllers\Doctor\ParentController;
use App\Http\Controllers\Doctor\ChatController;
use App\Http\Controllers\Doctor\ChildController;
use App\Http\Controllers\Doctor\DoctorComplaintController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Parent\ParentRequestController;
use App\Http\Controllers\NotificationSettingController;

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Main Pages & Static Views
    |--------------------------------------------------------------------------
    */
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', fn() => view('doctor.settings'))->name('settings');
    Route::get('/doctor-profile', fn() => view('doctor.doctor-profile'))->name('doctor-profile');
   // Route::get('/privacy', fn() => view('doctor.privacy'))->name('privacy');

    /*
    |--------------------------------------------------------------------------
    | Doctor Requests
    |--------------------------------------------------------------------------
    */
    Route::get('/request', [DoctorRequestController::class, 'index'])->name('request');
    Route::delete('/request/{id}/cancel', [DoctorRequestController::class, 'cancel'])->name('request.cancel');
    
    /*
    |--------------------------------------------------------------------------
    | Parents & Children
    |--------------------------------------------------------------------------
    */
    Route::get('/parents', [ParentController::class, 'index'])->name('parents');
    Route::get('/parent-profile/{id}', [ParentController::class, 'show'])->name('parent.profile');
    Route::get('/parents/search/ajax', [ParentController::class, 'searchAjax'])->name('parents.search.ajax');
    Route::delete('/parents/{id}/remove', [ParentController::class, 'removeParent'])->name('parent.remove');
    
    Route::get('/children/search', [ChildController::class, 'searchPage'])->name('children.search');
    Route::get('/children/find', [ChildController::class, 'find'])->name('children.find');
    Route::post('/children/{id}/attach', [ChildController::class, 'attach'])->name('children.attach');

    /*
    |--------------------------------------------------------------------------
    | Chat
    |--------------------------------------------------------------------------
    */
    Route::get('/chat/{parentId}', [ChatController::class, 'show'])->name('chat');
    Route::get('/chat/{parentId}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{parentId}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/message/{messageId}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');
    Route::post('/chat/{parentId}/send-audio', [ChatController::class, 'sendAudio'])->name('chat.sendAudio');
    Route::post('/chat/{parentId}/mute', [ChatController::class, 'muteConversation'])->name('chat.mute');
    /*
    |--------------------------------------------------------------------------
    | Appointments Management
    |--------------------------------------------------------------------------
    */
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/add-appointment', [AppointmentController::class, 'create'])->name('add.appointment');
    Route::post('/add-appointment', [AppointmentController::class, 'store'])->name('add.appointment.store');
    Route::get('/edit-appointment/{id}', [AppointmentController::class, 'edit'])->name('edit.appointment');
    Route::put('/update-appointment/{id}', [AppointmentController::class, 'update'])->name('update.appointment');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    /*
    |--------------------------------------------------------------------------
    | Workplace Timings
    |--------------------------------------------------------------------------
    */
    Route::get('/workplace-timing', [WorkplaceController::class, 'index'])->name('workplace.timing');
    Route::get('/add-workplace', [WorkplaceController::class, 'create'])->name('add.workplace');
    Route::post('/add-workplace', [WorkplaceController::class, 'store'])->name('add.workplace.store');
    Route::get('/edit-workplace/{id}', [WorkplaceController::class, 'edit'])->name('edit-workplace');
    Route::put('/workplace-update/{id}', [WorkplaceController::class, 'update'])->name('workplace.update');
    Route::delete('/workplace/delete/{id}', [WorkplaceController::class, 'destroy'])->name('workplace.delete');

    /*
    |--------------------------------------------------------------------------
    | Profile, Security & Alert Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/edit-profile', [DoctorProfileController::class, 'edit'])->name('edit-profile');
    Route::put('/edit-profile', [DoctorProfileController::class, 'update'])->name('edit-profile.update');
    Route::get('/change-password', [DoctorProfileController::class, 'editPassword'])->name('password');
    Route::post('/change-password', [DoctorProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/delete-account', [DoctorProfileController::class, 'destroyAccount'])->name('delete.account');
    Route::post('/logout', [DoctorProfileController::class, 'logout'])->name('logout');
    
    // تم اعتماد هذا الراوت لفتح واجهة الأصوات
    Route::get('/alert-sounds', function () {
        return view('doctor.alert-sounds'); 
    })->name('alert-sounds');

    /*
    |--------------------------------------------------------------------------
    | Complaints
    |--------------------------------------------------------------------------
    */
    Route::get('/complaint', [DoctorComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaint', [DoctorComplaintController::class, 'store'])->name('complaints.store');
});

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes (Parent & Doctor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/settings/toggle', [NotificationSettingController::class, 'toggleSetting'])->name('settings.toggle');
    
    Route::post('/parent/requests/{id}/accept', [ParentRequestController::class, 'accept'])->name('parent.requests.accept');
    Route::post('/parent/requests/{id}/reject', [ParentRequestController::class, 'reject'])->name('parent.requests.reject');
});

Route::get('/privacy', function () {
    return view('doctor.privacy');
})->name('privacy');