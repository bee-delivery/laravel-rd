<?php

namespace Beedelivery\RD;

use Beedelivery\RD\Commands\PullFromPubSubRD;
use Illuminate\Support\ServiceProvider;

class RDServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-rd');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-rd');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rd.php' => config_path('rd.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-rd'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-rd'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-rd'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                PullFromPubSubRD::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/rd.php', 'rd');

        // Register the main class to use with the facade
        $this->app->singleton('rd', function () {
            return new RD;
        });
    }
}
