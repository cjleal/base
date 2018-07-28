<?php

namespace Modules\Movil\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'movil');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'movil');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'movil');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
