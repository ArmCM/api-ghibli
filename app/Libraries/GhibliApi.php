<?php

namespace App\Libraries;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class GhibliApi
{
    use ApiResponses;

    public function makeRequest(string $endPoint, $parameters): JsonResponse
    {
        $response = Http::get("https://ghibliapi.vercel.app/$endPoint", $parameters);

        return $this->processApiResponse($response);
    }

    protected function processApiResponse($response): JsonResponse
    {
        return match (true) {
            $response->failed() => $this->error(
                'No se pudo conectar al servicio externo.',
                $response->status(),
            ),
            empty(json_decode($response)) => $this->error(
                'No se encontraron resultados.',
                $response->status(),
            ),
            default => $this->success(
                'Vehículos encontrados',
                $response->json(),
                $response->status(),
            ),
        };
    }
}
