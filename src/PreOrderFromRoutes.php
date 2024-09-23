<?php

namespace Peyas\PreOrderForm;
use Illuminate\Support\Facades\Route;
use Peyas\PreOrderForm\Http\Controllers\PreOrderController;

class PreOrderFromRoutes
{
    public static function routes(array $options = []): void
    {
        if (in_array('pre-order-store', $options)) {
            self::preOrderStoreRoute();
        }

        if (in_array('pre-order-index-show-view', $options)) {
            self::preOrdersIndexShowViewRoutes();
        }

        if (in_array('pre-order-delete', $options)) {
            self::preOrdersDeleteRoutes();
        }

    }

    // Route for the 'store' action (without Sanctum middleware)
    protected static function preOrderStoreRoute()
    {
        Route::post('pre-order', [PreOrderController::class, 'store'])->name('pre-order.store');
    }

    // Routes for the 'index' and 'show' actions (with Sanctum middleware)
    protected static function preOrdersIndexShowViewRoutes()
    {
        Route::apiResource('pre-order', PreOrderController::class)
            ->only(['index', 'show','view']);
    }

    // Route for the 'destroy' action (with Sanctum middleware)
    protected static function preOrdersDeleteRoutes()
    {
        Route::delete('pre-order/{preOrder}', [PreOrderController::class, 'destroy']);
    }
}
