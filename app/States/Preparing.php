<?php

namespace App\States;

class Preparing extends OrderState
{
    public function name(): string
    {
        return 'preparing';
    }
}