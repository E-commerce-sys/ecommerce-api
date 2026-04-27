<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends ApiController
{
    public function store(LoginRequest $request) {
       $validated = $request->validated();

        if (! Auth::attempt($validated)) {
            return $this->error('Invalid credentials!', 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->hasVerifiedEmail()) {
            return $this->error([
                'message' => 'Please verify your email address first.',
                'userId' => $user->id,
                'status' => 403
            ], 403);
        }

        if (!is_null($user->blocked_at)) {
            return $this->error([
                'message' => 'Your account has been blocked.',
                'status' => 403
            ], 403);
        }

        $token = $user->createToken(
            $user->email,
        )->plainTextToken;

        return $this->success(
            [
                'token' => $token,
                'user' => new UserResource($user)
            ],
            'Logged in successfully!',
            201
        );
    } 


    public function destroy(Request $request) {
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return $this->ok([], 'User token deleted successfully!');
    }
}
