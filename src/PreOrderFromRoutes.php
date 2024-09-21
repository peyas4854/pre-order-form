<?php

namespace Peyas\PreOrderForm;

use Illuminate\Support\Facades\Route;
use Peyas\PreOrderForm\Http\Controllers\OrderController;


class PreOrderFromRoutes
{
    public static function routes(array $options = []): void
    {
        if (in_array('orders', $options)) {
            self::ordersRoutes();
        }

    }
    protected static function ordersRoutes()
    {
        return Route::apiResource('orders', OrderController::class)->only(['index']);
    }

}
