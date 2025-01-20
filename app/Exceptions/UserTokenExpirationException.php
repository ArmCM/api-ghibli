<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserTokenExpirationException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'El token a caducado, por favor intente de nuevo generando un nuevo token.',
                'errors' => [
                    'authorization' => 'Acceso denegado'
                ],
                'code' => 403
            ], \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN)
        );
    }
}
