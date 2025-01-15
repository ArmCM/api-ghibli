<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return $this->error('Credentials not match', 401);
        }

        $user = User::firstWhere('email', $request->validated('email'));

        return $this->ok('Authenticated', [
            'token' => $user->createToken('api token for'. $user->email)->plainTextToken,
        ]);
    }
}
