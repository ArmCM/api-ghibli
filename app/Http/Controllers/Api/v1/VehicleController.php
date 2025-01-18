<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\NullObjects\NullQueryString;
use App\UseCases\QueryVehicles;
use App\Utils\QueryStringFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    public string $nullQueryString;

    public function __construct(protected QueryVehicles $queryVehicles, NullQueryString $nullQueryString)
    {
        $this->nullQueryString = $nullQueryString->queryString();
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('view.all.vehicles');

        return $this->queryVehicles->process($request->only(['fields', 'limit']) ?? $this->nullQueryString);
    }

    public function show(Request $request, string $vehicleId): JsonResponse
    {
        Gate::authorize('view.detail.vehicles');

        $queryParameters = QueryStringFormat::toArray($request->query(), $vehicleId);

        return $this->queryVehicles->process($queryParameters);
    }
}
