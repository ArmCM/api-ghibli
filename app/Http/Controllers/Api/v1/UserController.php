<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreUserRequest;
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
