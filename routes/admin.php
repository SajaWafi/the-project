<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorManagementController;
use App\Http\Controllers\Admin\ParentManagementController;

use App\Http\Controllers\Admin\ChildrenManagementController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportsController;

use App\Http\Controllers\Admin\DoctorRequestManagementController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminAlertController;
use App\Http\Controllers\Admin\ProfileController;


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        //admin dashbord
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        //doctor managment
        Route::get('/doctors', [DoctorManagementController::class, 'index'])
            ->name('doctors.index');

         Route::post('/doctors', [DoctorManagementController::class, 'store'])
                        ->name('doctors.store');

        Route::put('/doctors/{doctor}', [DoctorManagementController::class, 'update'])
            ->name('doctors.update');

        Route::delete('/doctors/{doctor}', [DoctorManagementController::class, 'destroy'])
            ->name('doctors.destroy');


        //children managment
        Route::get('/children', [ChildrenManagementController::class, 'index'])->name('children.index');
        Route::put('/children/{id}', [ChildrenManagementController::class, 'update'])->name('children.update');
        Route::delete('/children/{id}', [ChildrenManagementController::class, 'destroy'])->name('children.destroy');

        //complaints managment
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::put('/complaints/{id}', [ComplaintController::class, 'update'])->name('complaints.update');
        Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');


        // parents managment


            Route::get('/parents', [ParentManagementController::class, 'index'])
                ->name('parents.index');

                Route::post('/parents', [ParentManagementController::class, 'store'])
                ->name('parents.store');

            Route::put('/parents/{parent}', [ParentManagementController::class, 'update'])
                ->name('parents.update');

            Route::delete('/parents/{parent}', [ParentManagementController::class, 'destroy'])
                ->name('parents.destroy');

            //DoctorRequestManagement
  
            Route::get('/doctor-requests', [DoctorRequestManagementController::class, 'index'])
                ->name('doctor-requests.index');

            Route::post('/doctor-requests/{requestItem}/accept', [DoctorRequestManagementController::class, 'accept'])
                ->name('doctor-requests.accept');

            Route::post('/doctor-requests/{requestItem}/reject', [DoctorRequestManagementController::class, 'reject'])
                ->name('doctor-requests.reject');

            Route::delete('/doctor-requests/{requestItem}', [DoctorRequestManagementController::class, 'destroy'])
                ->name('doctor-requests.destroy');

            //adminapprovdoctors

            Route::post('/doctors/{doctor}/approve', [DoctorManagementController::class, 'approve'])
                ->name('doctors.approve');

            Route::post('/doctors/{doctor}/reject', [DoctorManagementController::class, 'reject'])
                ->name('doctors.reject');

                // bracelets managment
                Route::get('/bracelets', [BraceletController::class, 'index'])->name('bracelets.index');

            //appointmentsMangegment
            Route::get('/appointments', [AdminAppointmentController::class, 'index'])
                    ->name('appointments.index');

            Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])
                    ->name('appointments.update');

            Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])
                    ->name('appointments.destroy');

            //alerts
            Route::get('/alerts', [AdminAlertController::class, 'index'])
                    ->name('alerts.index');

            Route::post('/alerts/{alert}/mark-read', [AdminAlertController::class, 'markRead'])
                    ->name('alerts.mark-read');

            Route::post('/alerts/{alert}/mark-unread', [AdminAlertController::class, 'markUnread'])
                    ->name('alerts.mark-unread');

            Route::delete('/alerts/{alert}', [AdminAlertController::class, 'destroy'])
                    ->name('alerts.destroy');



    
    // مسارات الإعدادات (Settings)
// مسارات الإعدادات (Settings)
    Route::get('/settings', [ProfileController::class, 'index'])->name('settings.index');
    Route::put('/settings/update', [ProfileController::class, 'update'])->name('settings.update');
    Route::delete('/settings/delete', [ProfileController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/add-admin', [ProfileController::class, 'storeAdmin'])->name('settings.storeAdmin');
    
    });


