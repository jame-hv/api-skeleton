<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

// Ensure that the logout route is protected by the auth:sanctum middleware
Route::post('logout', LogoutController::class)->name('logout')->middleware('auth:sanctum');
