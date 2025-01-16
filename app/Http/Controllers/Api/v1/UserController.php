<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreUserRequest;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
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

        return response()->json([
            'message' => 'Usuario creado exitosamente.',
        ], 201);
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return UserResource::make($user);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        tap(User::findOrFail($user->id), function ($user) use ($validatedData) {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->assignRole($validatedData['role']);
            $user->save();
        });

        return response()->json([
            'message' => 'Usuario actualizado exitosamente.',
        ], 201);
    }

    public function destroy(string $id)
    {
        //
    }
}
