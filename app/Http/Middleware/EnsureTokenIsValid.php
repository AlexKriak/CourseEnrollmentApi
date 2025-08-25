<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token || $token !== config('services.api_token')) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Требуется валидный Bearer-токен'
            ], 401)->setStatusCode(401);
        }

        return $next($request);
    }
}
