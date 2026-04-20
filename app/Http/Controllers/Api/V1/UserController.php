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
            ->orderBy('created_at', 'desc')
            ->doesntHave('roles')
            ->paginate(10)
        );
    }

    public function adminUpdate(UpdateUserRequest $request, $user_id) {
        $user = User::findOrFail($user_id);
        $this->authorize('update', $user);
        $user->Update($request->mappedAttributes());
        return new UserResource($user);
    }

    public function adminDestroy($user_id) {
        $user = User::findOrFail($user_id);
        $this->authorize('delete', $user);
        $user->delete();
        return $this->ok([], 'User deleted successfully!');
    }
    
    public function blockUser($user_id) {
        $user = User::findOrFail($user_id);
        $this->authorize('block', $user);
        $user->update([
            'blocked_at' => now()
        ]);
        return $this->ok([], 'User blocked successfully!');
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
