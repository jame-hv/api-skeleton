<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class LogApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);

        try {
            ApiLog::create([
                'request_id' => $request->header('X-Request-ID', Str::uuid()->toString()),
                'method' => $request->method(),
                'url' => $request->path(),
                'status' => $response->getStatusCode(),
                'time' => (int) ((microtime(true) - $start) * 1000),
                'request' => $request->expectsJson() ? $request->all() : null,
                'response' => 'application/json' === $response->headers->get('Content-Type')
                    ? json_decode($response->getContent(), true)
                    : null,
                'token' => $request->bearerToken(),
                'user_id' => $request->user()?->id,
            ]);
        } catch (Throwable $e) {
            FacadesLog::warning('API log failed', ['exception' => $e]);
        }

        return $response;
    }

}
