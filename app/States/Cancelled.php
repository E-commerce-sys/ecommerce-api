<?php

namespace App\States;

class Cancelled extends OrderState
{
    public static $name = 'Cancelled';
    public static function next(): ?string
    {
        return null;
    }
}