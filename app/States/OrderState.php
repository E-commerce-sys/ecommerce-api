<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    abstract public static function next(): ?string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)

            ->registerState(Pending::class)
            ->registerState(Preparing::class)
            ->registerState(Shipping::class)
            ->registerState(Delivering::class)
            ->registerState(Cancelled::class)

            ->allowTransition(Pending::class, Preparing::class) 
            ->allowTransition(Preparing::class, Shipping::class) 
            ->allowTransition(Shipping::class, Delivering::class) 
            ->allowTransition(Delivering::class, Arrived::class) 
            ->allowTransition(Pending::class, Cancelled::class) 
            ->allowTransition(Preparing::class, Cancelled::class)
        ;
    }
}