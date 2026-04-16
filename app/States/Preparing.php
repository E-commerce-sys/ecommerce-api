<?php

namespace App\States;

class Preparing extends OrderState
{
    public static function next(): string
    {
        return Shipping::class;
    }
}