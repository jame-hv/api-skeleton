<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Actions\Auth\CreateUserAction;
use App\DTOs\Auth\RegisterUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Queue\Queueable;

final class CreateUser
{
    use Queueable;

    public function __construct(private RegisterUser $payload)
    {

    }

    public function handle(CreateUserAction $action, Dispatcher $event): void
    {
        $user = $action->handle(payload : $this->payload);

        $event->dispatch(
            event: new Registered(user: $user),
        );
    }

}
