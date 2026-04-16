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

            ->allowTransition(Pending::class, Pending::next()) 
            ->allowTransition(Preparing::class, Preparing::next()) 
            ->allowTransition(Shipping::class, Shipping::next()) 
            ->allowTransition(Delivering::class, Delivering::next()) 
            ->allowTransition(Pending::class, Cancelled::class) 
            ->allowTransition(Preparing::class, Cancelled::class)
        ;
    }
}