<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorManagementController;
use App\Http\Controllers\Admin\ParentManagementController;
use App\Http\Controllers\Admin\ChildrenManagementController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportsController;


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

        Route::put('/doctors/{doctor}', [DoctorManagementController::class, 'update'])
            ->name('doctors.update');

        Route::delete('/doctors/{doctor}', [DoctorManagementController::class, 'destroy'])
            ->name('doctors.destroy');

        //parents managment
        Route::get('/parents', [ParentManagementController::class, 'index'])->name('parents.index');
        Route::put('/parents/{id}', [ParentManagementController::class, 'update'])->name('parents.update');
        Route::delete('/parents/{id}', [ParentManagementController::class, 'destroy'])->name('parents.destroy');

        //children managment
        Route::get('/children', [ChildrenManagementController::class, 'index'])->name('children.index');
        Route::put('/children/{id}', [ChildrenManagementController::class, 'update'])->name('children.update');
        Route::delete('/children/{id}', [ChildrenManagementController::class, 'destroy'])->name('children.destroy');

        //complaints managment
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    });