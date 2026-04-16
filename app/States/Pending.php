<?php

namespace App\States;

class Pending extends OrderState
{
    public static function next(): string
    {
        return Preparing::class;
    }
}