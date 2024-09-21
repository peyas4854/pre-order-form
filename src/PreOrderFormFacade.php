<?php

namespace Peyas\PreOrderForm;

use Illuminate\Support\Facades\Facade;

class PreOrderFormFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pre-order-form';
    }

}
