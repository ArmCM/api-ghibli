<?php

namespace App\UseCases;

use App\Libraries\GhibliApi;
use Illuminate\Http\JsonResponse;

class QueryFilms
{
    public function __construct(protected GhibliApi $ghibliApi)
    {
    }

    public function process(array $parameters): JsonResponse
    {
        return $this->ghibliApi->makeRequest('films', $parameters);
    }
}
