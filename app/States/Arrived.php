<?php

namespace App\States;

class Arrived extends OrderState
{
    public static function next(): ?string
    {
        return null;
    }
}