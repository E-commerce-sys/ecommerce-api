<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Models\User;

class RegisterUserController extends ApiController
{
    public function store(RegisterUserRequest $request) {
        $user = User::create($request->mappedAttributes());

        $token = $user->createToken($user->email)->plainTextToken;

        return $this->success(
            [
                'token' => $token,
                'user' => $user
            ],
            'User registered successfully',
            201
        );
    }
}
