<?php
namespace Peyas\PreOrderForm;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PreOrderFormServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('pre-order-form', function () {
            return new PreOrderForm();
        });

        // Automatically add the facade alias
//        $loader = AliasLoader::getInstance();
//        $loader->alias('ShopAdmin', PreOrderFormFacade::class);

        // Merge the package config with the application's config
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/PreOrderForm.php', 'PreOrderForm');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // config publish
        $this->publishes([
            __DIR__ . '/../config/PreOrderForm.php' => config_path('PreOrderForm.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }
}


