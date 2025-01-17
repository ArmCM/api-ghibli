<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use App\Utils\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class FilmController extends Controller
{
    use ApiResponses;

    public function index(Request $request)
    {
        Gate::authorize('view.all.films');

        $queryParameters = QueryString::toArray($request->getQueryString());

        $response = Http::get('https://ghibliapi.vercel.app/films', $queryParameters);

        if ($response->failed()) {
            $this->error('No se pudo conectar al servicio externo.', $response->status());
        }

        return $response->json();
    }
}
