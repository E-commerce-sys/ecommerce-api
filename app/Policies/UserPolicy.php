<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('user:list');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('user:update');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('user:delete') && !$model->hasRole('super_admin');
    }

    public function block(User $user, User $model): bool
    {
        return $user->can('user:delete') && !$model->hasRole('super_admin');
    }

    public function listStaff(User $user) {
        return $user->can('admin:list');
    }

    public function createAdmin(User $user) {
        return $user->can('admin:create');
    }
}
