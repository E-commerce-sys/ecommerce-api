<?php

namespace App\States;

use Spatie\ModelStates\State;

abstract class OrderState extends State
{
    abstract public function name(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class)
        ;
    }
}