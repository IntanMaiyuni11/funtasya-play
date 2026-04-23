<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // cek apakah user login
        if (!$request->user()) {
            abort(403);
        }

        // cek role
        if (!in_array($request->user()->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}