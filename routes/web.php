<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('web.auth.login');

});
Route::get('auth/google',[\App\Http\Controllers\Web\Auth\AuthController::class,'redirectToGoogle'])->name('authGoogle');
Route::get('auth/google/callback',[\App\Http\Controllers\Web\Auth\AuthController::class,'handGoogleCallback']);

