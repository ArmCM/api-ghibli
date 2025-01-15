<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiFormRequest extends FormRequest
{
    /**
     * Handle a failed authorization.
     *
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'No está autorizado para realizar esta acción.',
                'errors' => [
                    'authorization' => 'Acceso denegado'
                ],
                'code' => 403
            ], Response::HTTP_FORBIDDEN)
        );
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $validator->errors(),
                'code' => 422
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
