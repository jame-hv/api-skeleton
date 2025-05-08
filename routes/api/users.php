<?php

declare(strict_types=1);

use App\Http\Controllers\Profile\EditProfileController;
use App\Http\Controllers\Profile\ShowProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('/profile')->as('profile:')->group(static function (): void {
    Route::get('/{user}', ShowProfileController::class)->name('show.profile');
    Route::put('/{user}', EditProfileController::class)->name('edit.profile')->middleware('can:update,user');
});
