<?php

declare(strict_types=1);

namespace App\Enums\Profile;

enum Gender: int
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;
}
