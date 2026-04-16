<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('product:list');
    }

    public function update(User $user): bool
    {
        return $user->can('order:update');
    }

}
