<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CouponPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('coupon:list', Coupon::class);
    }

    public function create(User $user): bool
    {
        return $user->can('coupon:create');
    }
}
