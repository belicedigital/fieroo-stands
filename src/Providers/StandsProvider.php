<?php

namespace Fieroo\Stands\Providers;

use Illuminate\Support\ServiceProvider;

class StandsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../views', 'stands');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
    }
}