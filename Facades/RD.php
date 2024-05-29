<?php

namespace BeeDelivery\RD\Facades;

use Illuminate\Support\Facades\Facade;

class RD extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rd';
    }
}