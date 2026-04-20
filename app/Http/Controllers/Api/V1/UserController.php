<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends ApiController
{
    public function index() {
        $this->authorize('viewAny', User::class);
        return UserResource::collection(
            QueryBuilder::for(User::class)
            ->allowedIncludes(User::allowedIncludes())
            ->allowedFilters(User::allowedFilters())
            ->with(['orders', 'roles'])
            ->paginate(10)
        );
    }

    public function show() {
        $user = auth('sanctum')->user();
        return new UserResource(
            $user
        );
    }

    public function update(UpdateUserRequest $request) {
        // PATCH
        $user = auth('sanctum')->user();
        $user->Update($request->mappedAttributes());
        return new UserResource($user);
    }

    public function destroy() {
        $user = auth('sanctum')->user();
        $user->delete();
        return $this->ok([], 'User deleted successfully!');
    }
}
