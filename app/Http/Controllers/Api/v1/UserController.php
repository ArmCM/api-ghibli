<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreUserRequest;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use App\UseCases\UserRegistration;
use App\UseCases\UserUpdate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    use ApiResponses;

    public function __construct(protected UserRegistration $registerUser, protected UserUpdate $userUpdate)
    {

    }

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $collection = User::paginateWithRoles();

        return $this->success(
            'Usuarios recuperados exitosamente.',
            $collection->items(),
            200,
            [
                'links' => [
                    'first' => $collection->url(1),
                    'last' => $collection->url($collection->lastPage()),
                    'prev' => $collection->previousPageUrl(),
                    'next' => $collection->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $collection->currentPage(),
                    'last_page' => $collection->lastPage(),
                    'per_page' => $collection->perPage(),
                    'total' => $collection->total(),
                ],
            ],
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->registerUser->register($request->validated());

        return $this->success(
            'Usuario creado exitosamente.',
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()->name,
            ],
            201,
        );
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('view', $user);

        return $this->success(
            'Usuario encontrado.',
            [
                'type' => 'user',
                'id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'links' => [
                    'self' => route('users.show', ['user' => $user->id]),
                ],
            ]
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        Gate::authorize('update', $user);

        $this->userUpdate->update($request->validated(), $user->id);

        return $this->success('Usuario actualizado exitosamente.', [], 201);
    }

    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return $this->success('Usuario eliminado exitosamente.');
    }
}
