<?php

namespace App\States;

class Preparing extends OrderState
{
    public function next(): string
    {
        return Shipping::class;
    }
}