<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {

        $request->authenticate();

        $token = $request->user()?->createToken(
            name: 'API Access Token',
            abilities: ['*'],
        );

        return new JsonResponse(
            data: [
                'token' => $token->plainTextToken,
            ],
        );
    }
}
