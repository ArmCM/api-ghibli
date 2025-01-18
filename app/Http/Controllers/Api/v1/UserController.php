<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreUserRequest;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponses;

    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);

        return UserResource::collection(User::paginate());
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        tap(User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]), function ($user) use ($validatedData) {
            $user->assignRole($validatedData['role']);
        });

        return $this->success('Usuario creado exitosamente.', [], 201);
    }

    public function show(User $user): UserResource
    {
        Gate::authorize('view', $user);

        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        Gate::authorize('update', $user);

        $validatedData = $request->validated();

        tap(User::findOrFail($user->id), function ($user) use ($validatedData) {
            $user->fill(collect($validatedData)
                ->only(['name', 'email'])
                ->when($validatedData['password'] ?? null, function ($collection) use ($validatedData) {
                    return $collection->put('password', Hash::make($validatedData['password']));
                })
                ->toArray()
            );

            optional($validatedData)['role'] && $user->syncRoles($validatedData['role']);


            $user->save();
        });

        return $this->success('Usuario actualizado exitosamente.', [], 201);
    }

    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return $this->success('Usuario eliminado exitosamente.');
    }
}
