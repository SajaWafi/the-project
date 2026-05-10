<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorManagementController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/doctors', [DoctorManagementController::class, 'index'])
            ->name('doctors.index');

        Route::put('/doctors/{doctor}', [DoctorManagementController::class, 'update'])
            ->name('doctors.update');

        Route::delete('/doctors/{doctor}', [DoctorManagementController::class, 'destroy'])
            ->name('doctors.destroy');
    });