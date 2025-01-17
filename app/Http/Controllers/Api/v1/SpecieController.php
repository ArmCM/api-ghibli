<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QuerySpecies;
use App\Utils\QueryStringFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SpecieController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QuerySpecies $querySpecies, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('view.all.species');

        return $this->querySpecies->process($request->only(['fields', 'limit']) ?? $this->nullQueryString);
    }

    public function show(Request $request, string $filmId): JsonResponse
    {
        Gate::authorize('view.all.species');

        $queryParameters = QueryStringFormat::toArray($request->query(), $filmId);

        return $this->querySpecies->process($queryParameters);
    }
}
