<?php

declare(strict_types=1);

namespace App\DTOs\Profile;

use App\Enums\Profile\Gender;

final readonly class EditProfile
{
    public function __construct(
        public ?string $day_of_birth,
        public ?Gender $gender,
        public ?string $phone,
        public ?string $profile_image,
    ) {
    }

    public function toArray(): array
    {
        return [
            'day_of_birth' => $this->day_of_birth,
            'gender' => $this->gender?->value,
            'phone' => $this->phone,
            'profile_image' => $this->profile_image,
        ];
    }

}
