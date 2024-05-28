<?php

namespace BeeDelivery\RaiaDrograsil\Facades;

use Illuminate\Support\Facades\Facade;

class RaiaDrogasil extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'raiadrogasil';
    }
}