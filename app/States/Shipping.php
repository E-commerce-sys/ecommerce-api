<?php

namespace App\States;

class Shipping extends OrderState
{
    public function next(): string
    {
        return Delivering::class;
    }
}