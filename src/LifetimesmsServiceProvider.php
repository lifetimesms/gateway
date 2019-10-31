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
        $this->app->bind('lifetimesms', function(){
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
            # code...
            $this->registerPublishing();
        }
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/lifetimesms.php' => config_path('lifetimesms.php'),
        ], 'lifetimesms');
    }
}
