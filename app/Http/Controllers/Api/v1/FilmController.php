<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QueryFilms;
use App\Utils\QueryStringFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FilmController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QueryFilms $queryFilms, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request)
    {
        Gate::authorize('view.all.films');

        $queryParameters = QueryStringFormat::toArray($request->getQueryString() ?? $this->nullQueryString);

        return $this->queryFilms->process($queryParameters);
    }
}
