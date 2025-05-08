<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! $request->user() || ! $request->user()->hasVerifiedEmail()) {
            return new JsonResponse(
                data: [
                    'message' => trans('auth.email_verification_required'),
                ],
                status: Response::HTTP_CONFLICT,
            );
        }

        return $next($request);

    }
}
