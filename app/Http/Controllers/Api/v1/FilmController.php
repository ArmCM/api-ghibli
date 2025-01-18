<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QueryFilms;
use App\Utils\QueryStringFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FilmController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QueryFilms $queryFilms, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('view.all.films');

        return $this->queryFilms->process($request->only(['fields', 'limit']) ?? $this->nullQueryString);
    }

    public function show(Request $request, string $filmId): JsonResponse
    {
        Gate::authorize('show.detail.films');

        $queryParameters = QueryStringFormat::toArray($request->query(), $filmId);

        return $this->queryFilms->process($queryParameters);
    }
}
