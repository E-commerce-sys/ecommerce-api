<?php

namespace App\States;

class Shipping extends OrderState
{
    public function name(): string
    {
        return 'shipping';
    }
}