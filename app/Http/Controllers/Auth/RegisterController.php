<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Jobs\Auth\CreateUser;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController extends Controller
{
    public function __construct(private Dispatcher $bus)
    {
    }


    public function __invoke(RegistrationRequest $request): Response
    {
        defer(
            callback: fn () => $this->bus->dispatch(
                command: new CreateUser(
                    payload: $request->payload(),
                ),
            ),
        );

        return new JsonResponse(
            data: [
                'message' => trans('auth.registering'),
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
