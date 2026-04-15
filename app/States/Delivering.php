<?php

namespace App\States;

class Delivering extends OrderState
{
    public function name(): string
    {
        return 'delivering';
    }
}