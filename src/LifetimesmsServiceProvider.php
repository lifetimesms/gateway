<?php

namespace Lifetimesms\Gateway;

use Lifetimesms\Gateway\Lifetimesms;
use Illuminate\Support\ServiceProvider;

class LifetimesmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Binding the service to the container
        $this->app->singleton(Lifetimesms::class, function ($app) {
            return new Lifetimesms();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register the publishing of configuration file.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/lifetimesms.php' => config_path('lifetimesms.php'),
        ], 'lifetimesms');
    }
}
