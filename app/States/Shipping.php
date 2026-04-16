<?php

namespace App\States;

class Shipping extends OrderState
{
    public static function next(): string
    {
        return Delivering::class;
    }
}