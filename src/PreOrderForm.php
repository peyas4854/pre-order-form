<?php

namespace Peyas\PreOrderForm;

class PreOrderForm
{
    public static function routes(array $options = []): void
    {
        $saRoutes = new PreOrderFromRoutes();
        $saRoutes::routes($options);
    }

}
