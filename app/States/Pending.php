<?php

namespace App\States;

class Pending extends OrderState
{
    public function name(): string
    {
        return 'pending';
    }
}