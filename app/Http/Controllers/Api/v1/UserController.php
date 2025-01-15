<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //
    }
    
    public function create()
    {
        //
    }

    public function store(StoreUserRequest $request)
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

    public function show(string $id)
    {
        //
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
