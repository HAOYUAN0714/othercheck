<?php

namespace App\Providers\TelegramObject;

use Illuminate\Support\ServiceProvider;

class TelegramObjectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('telegram_object', function ($app) {
            return new TelegramObject();
        });
    }
}
