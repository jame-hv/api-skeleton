<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['log-requests'])->group(static function (): void {
    Route::prefix('auth')->as('auth:')->group(base_path(
        path: 'routes/api/auth.php',
    ));

    Route::middleware(('auth:sanctum'))->group(
        static function (): void {
            Route::prefix('users')->as('profile:')->group(base_path(
                path: 'routes/api/users.php',
            ));
        },
    );

});
