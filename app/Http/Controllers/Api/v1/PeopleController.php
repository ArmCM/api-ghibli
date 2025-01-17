<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QueryPeople;
use App\Utils\QueryStringFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeopleController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QueryPeople $queryPeople, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('view.all.people');

        return $this->queryPeople->process($request->only(['fields', 'limit']) ?? $this->nullQueryString);
    }

    public function show(Request $request, string $filmId): JsonResponse
    {
        Gate::authorize('view.all.people');

        $queryParameters = QueryStringFormat::toArray($request->query(), $filmId);

        return $this->queryPeople->process($queryParameters);
    }
}
