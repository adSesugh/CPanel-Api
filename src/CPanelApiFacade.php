<?php

namespace Keensoen\CPanelApi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Keensoen\CPanelApi\Skeleton\SkeletonClass
 */
class CPanelApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cpanel-api';
    }
}
