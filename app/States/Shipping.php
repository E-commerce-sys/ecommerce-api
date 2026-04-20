<?php

namespace App\States;

class Shipping extends OrderState
{
    public static $name = 'Shipping';
    public static function next(): string
    {
        return Delivering::class;
    }
}