<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
  //  return view('welcome');
//});


Route::get('/', function () {
    return view('welcome-screen');
});
Route::get('/welcome-second', function () {
    return view('welcome-second');
})->name('welcome.second');
Route::get('/login-page', function () {
    return view('login-page');
})->name('login.page');