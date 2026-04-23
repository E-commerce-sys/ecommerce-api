<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Resources\Api\V1\StaffResource;
use App\Http\Resources\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class UserManagementController extends ApiController
{
    public function index(): AnonymousResourceCollection
    {
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

    public function update(UpdateUserRequest $request, int $userId): UserResource
    {
        $user = User::findOrFail($userId);

        $this->authorize('update', $user);

        $user->update($request->mappedAttributes());

        return new UserResource($user);
    }

    public function destroy(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $this->authorize('delete', $user);

        $user->delete();

        return $this->ok([], 'User deleted successfully!');
    }

    public function block(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $this->authorize('block', $user);

        if (! is_null($user->blocked_at)) {
            return $this->error('User is already blocked', 409);
        }

        $user->update([
            'blocked_at' => now(),
        ]);

        return $this->ok([], 'User blocked successfully!');
    }

    public function unblock(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $this->authorize('block', $user);

        if (is_null($user->blocked_at)) {
            return $this->error('User is not blocked', 409);
        }

        $user->update([
            'blocked_at' => null,
        ]);

        return $this->ok([], 'User unblocked successfully!');
    }

    public function staff(): AnonymousResourceCollection
    {
        $this->authorize('listStaff', User::class);

        return StaffResource::collection(
            QueryBuilder::for(User::class)
                ->allowedIncludes(User::allowedIncludes())
                ->allowedFilters(User::allowedFilters())
                ->with(['roles', 'roles.permissions', 'permissions'])
                ->orderBy('created_at', 'desc')
                ->has('roles')
                ->paginate(10)
        );
    }

    public function createAdmin(StoreUserRequest $request): UserResource
    {
        $this->authorize('createAdmin', User::class);
        $user = User::create($request->mappedAttributes());
        $user->assignRole('admin');
        return new UserResource($user);
    }
}
