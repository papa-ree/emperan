<?php

namespace Paparee\BaleEmperan\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Paparee\BaleEmperan\BaleEmperan
 */
class BaleEmperan extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Paparee\BaleEmperan\BaleEmperan::class;
    }
}
