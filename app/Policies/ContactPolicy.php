<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('contact:view', Contact::class);
    }
}
