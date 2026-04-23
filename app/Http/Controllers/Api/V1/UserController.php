<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;

class UserController extends ApiController
{
    public function show()
    {
        $user = auth('sanctum')->user();

        return new UserResource(
            $user
        );
    }

    public function update(UpdateUserRequest $request)
    {
        // PATCH
        $user = auth('sanctum')->user();
        $user->Update($request->mappedAttributes());

        return new UserResource($user);
    }

    public function destroy()
    {
        $user = auth('sanctum')->user();
        $user->delete();

        return $this->ok([], 'User deleted successfully!');
    }
}
