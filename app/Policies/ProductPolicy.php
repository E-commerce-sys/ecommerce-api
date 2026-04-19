<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function create(User $user): bool
    {
        return $user->can('product:create');
    }

    public function replace(User $user, Product $product): bool
    {
        return $user->can('product:replace');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('product:update');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('product:delete');
    }
}
