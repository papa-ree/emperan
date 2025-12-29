<?php

namespace Bale\Emperan\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bale\Emperan\Emperan
 */
class Emperan extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bale\Emperan\Emperan::class;
    }
}
