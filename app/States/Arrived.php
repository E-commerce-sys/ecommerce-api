<?php

namespace App\States;

class Arrived extends OrderState
{
    public function name(): string
    {
        return 'arrived';
    }
}