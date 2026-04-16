<?php

namespace App\States;

class Delivering extends OrderState
{
    public static function next(): ?string
    {
        return Arrived::class;
    }
}