<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Rbac\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, Permission $permission): Response
    {

        if ( ! $request ->user() || $request->user()->hasPermission($permission)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
