<?php

namespace App\States;

class Pending extends OrderState
{
    public function next(): string
    {
        return Preparing::class;
    }
}