<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomHttpNotFound
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() === Response::HTTP_NOT_FOUND && $request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'message' => 'La ruta solicitada no existe.',
            ], 404);
        }

        return $response;
    }
}
