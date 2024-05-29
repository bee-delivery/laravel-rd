<?php

namespace Beedelivery\RD;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Beedelivery\RD\Skeleton\SkeletonClass
 */
class RDFacade extends Facade
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
