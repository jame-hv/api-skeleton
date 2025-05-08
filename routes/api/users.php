<?php

declare(strict_types=1);

use App\Http\Controllers\Profile\EditProfileController;
use App\Http\Controllers\Profile\ShowProfileController;
use App\Http\Controllers\User\ListUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/profile')->as('profile:')->group(static function (): void {
    Route::get('/{user}', ShowProfileController::class)->name('show.profile');
    Route::put('/{user}', EditProfileController::class)->name('edit.profile')->middleware('can:update,user');
});

Route::get('/', ListUserController::class)->name('list.user');
