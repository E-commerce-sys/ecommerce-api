<?php

namespace App\States;

class Preparing extends OrderState
{
    public static $name = 'Preparing';
    public static function next(): string
    {
        return Shipping::class;
    }
}