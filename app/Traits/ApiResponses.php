<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function ok($message, $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    protected function success($message, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode
        ], $statusCode);
    }

    protected function error($message, $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status_code' => $statusCode
        ], $statusCode);
    }
}
