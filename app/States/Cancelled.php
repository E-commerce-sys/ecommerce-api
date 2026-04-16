<?php

namespace App\States;

class Cancelled extends OrderState
{
    public function next(): ?string
    {
        return null;
    }
}