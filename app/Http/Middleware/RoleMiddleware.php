<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!in_array(auth('api')->user()->role, $roles)) {
            return response()->json([
                'message' => 'Accès refusé — permissions insuffisantes.',
            ], 403);
        }

        return $next($request);
    }
}
