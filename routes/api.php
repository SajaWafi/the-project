<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;

// الرابط اللي بتستعمله الإسوارة: http://127.0.0.1:8000/api/bracelet/data
Route::post('/api/sensor/readings', [SensorController::class, 'receiveData']);