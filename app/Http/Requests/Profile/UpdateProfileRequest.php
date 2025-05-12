<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use App\DTOs\Profile\EditProfile;
use App\Enums\Profile\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'day_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'gender' => ['nullable', 'integer', new Enum(Gender::class)],
            'phone' => ['nullable', 'string', 'max:15'],
            'profile_image' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function payload(): EditProfile
    {
        return new EditProfile(
            day_of_birth: $this->input('day_of_birth'),
            gender: $this->whenHas('gender', fn () => Gender::from((int) $this->input('gender'))),
            phone: $this->input('phone'),
            profile_image: $this->input('profile_image'),
        );
    }
}
