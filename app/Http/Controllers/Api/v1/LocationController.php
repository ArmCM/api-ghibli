<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QueryLocations;
use App\Utils\QueryStringFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LocationController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QueryLocations $queryLocations, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('view.all.locations');

        return $this->queryLocations->process($request->only(['fields', 'limit']) ?? $this->nullQueryString);
    }

    public function show(Request $request, string $filmId): JsonResponse
    {
        Gate::authorize('view.detail.locations');

        $queryParameters = QueryStringFormat::toArray($request->query(), $filmId);

        return $this->queryLocations->process($queryParameters);
    }
}
