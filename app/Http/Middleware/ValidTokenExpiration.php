<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/v1/login')) {
            return $next($request);
        }

        if (is_null(Auth::guard('sanctum')->user())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token ha expirado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
