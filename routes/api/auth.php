<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Responses\MessageResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

// Ensure that the logout route is protected by the auth:sanctum middleware
Route::post('logout', LogoutController::class)->name('logout')->middleware('auth:sanctum');

// Email Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return new MessageResponse(message:'Email verified successfully.');
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return new MessageResponse(message:'Verification email resent successfully.');
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
