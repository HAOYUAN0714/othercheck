<?php

namespace App\Providers\Curl;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Providers\Curl\Curl;

class CurlServiceProvider extends ServiceProvider
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
        /* ------------------------------------------------------------------------------
        |
        |   Notice
        |
        |  新增連線設定時，需要到 config/logging.php 新增Log的檔案資料
        |
        ---------------------------------------------------------------------------------*/

        // Curl For IMSport
        $this->app->singleton('IMSport', function ($app) {
            $curl = new Curl();
            $curl->init(
                'IMSport',
                config('services.IMSport.ip'),
                config('services.IMSport.host'),
                config('services.IMSport.port'),
                config('services.IMSport.api-key'),
                config('services.IMSport.secure'),
                config('services.IMSport.resolve'),
                config('services.IMSport.proxy')
            );
            return $curl;
        });

        // Telegram
        $this->app->singleton('telegram', function ($app) {
            $curl = new Curl();
            $curl->init(
                'telegram',
                config('services.telegram.ip'),
                config('services.telegram.host'),
                config('services.telegram.port'),
                config('services.telegram.api-key'),
                config('services.telegram.secure'),
                config('services.telegram.resolve'),
                config('services.telegram.proxy')
            );
            return $curl;
        });
    }
}
