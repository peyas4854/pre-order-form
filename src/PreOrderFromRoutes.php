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

    }

    protected static function preOrdersRoutes()
    {
        return [
            Route::group(['prefix' => 'pre-order'], function () {
                Route::get('/', [PreOrderController::class, 'index']);
                Route::post('/', [PreOrderController::class, 'store'])->middleware('throttle:10,1');
            })
        ];
    }
}
