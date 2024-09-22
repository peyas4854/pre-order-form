<?php

namespace Peyas\PreOrderForm;
use Illuminate\Support\Facades\Route;
use Peyas\PreOrderForm\Http\Controllers\PreOrderController;

class PreOrderFromRoutes
{
    public static function routes(array $options = []): void
    {
        if (in_array('pre-order', $options)) {
            self::preOrdersRoutes();
        }
        if (in_array('pre-order-delete', $options)) {
            self::preOrdersDeleteRoutes();
        }

    }

    protected static function preOrdersRoutes()
    {
        Route::apiResource('pre-order', PreOrderController::class)->only(['index', 'store']);
    }

    protected static function preOrdersDeleteRoutes()
    {
        Route::delete('pre-order/{preOrder}', [PreOrderController::class, 'destroy']);

    }
}
