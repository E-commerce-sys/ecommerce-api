<?php

namespace App\States;

class Delivering extends OrderState
{
    public static $name = 'Delivering';
    public static function next(): ?string
    {
        return Arrived::class;
    }
}