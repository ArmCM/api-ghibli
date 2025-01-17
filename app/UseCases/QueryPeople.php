<?php

namespace App\UseCases;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class QueryPeople
{
    use ApiResponses;

    public function process(array $parameters): JsonResponse
    {
        $response = Http::get('https://ghibliapi.vercel.app/people', $parameters);

        if (empty(json_decode($response))) {
            return $this->error('No se encontraron resultados.', $response->status());
        }

        if ($response->failed()) {
            return $this->error('No se pudo conectar al servicio externo.', $response->status());
        }

        return $this->success('Personas encontradas', $response->json(), $response->status());
    }
}
