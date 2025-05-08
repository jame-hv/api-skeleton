<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\DataObjects\Profile\EditProfile;
use App\Models\User;
use Illuminate\Database\DatabaseManager;

final class EditProfileAction
{
    public function __construct(
        private DatabaseManager $database,
    ) {
    }

    public function handle(EditProfile $payload): bool
    {
        return (bool) $this->database->transaction(
            callback: fn () => User::query()->update(
                values: $payload->toArray(),
            ),
            attempts: 3,
        );
    }
}
