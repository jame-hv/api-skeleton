<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\SanctumServiceProvider;

return [
    AppServiceProvider::class,
    HorizonServiceProvider::class,
    SanctumServiceProvider::class,
];
