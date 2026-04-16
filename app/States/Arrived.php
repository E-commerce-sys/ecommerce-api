<?php

namespace App\States;

class Arrived extends OrderState
{
    public function next(): ?string
    {
        return null;
    }
}