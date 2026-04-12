<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return $user->can('category:create');
    }

    public function replace(User $user, Category $category): bool
    {
        return $user->can('category:replace');
    }
    
    public function update(User $user, Category $category): bool
    {
       return $user->can('category:update');
    }

   
    public function delete(User $user, Category $category): bool
    {
        return $user->can('category:delete');
    }
}
