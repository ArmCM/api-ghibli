<?php

namespace App\UseCases;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegistration
{
    public function register(array $validatedData)
    {
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->assignRole($validatedData['role']);

        return $user;
    }
}
