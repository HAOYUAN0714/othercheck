<?php

namespace App\Console;

use App\Http\Middleware\TerminateCurlHitCount;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        # 更新PC玩法數量
//        \App\Console\Commands\CreateBetCountForOpenParlay::class,
//        \App\Console\Commands\CreateBetCountNotOpenParlay::class,

        # 計算玩法數量
        \App\Console\Commands\StoreBetTypeCount::class,

        # 存取Widget賽事清單背景
        \App\Console\Commands\StoreWidgetEvent::class,

        # LiveStream
        ## 快取IM賽事清單
        \App\Console\Commands\LiveStream\CacheEventList::class,
        ## 快取直播賽事清單
        \App\Console\Commands\LiveStream\CacheLiveList::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    public function terminate($input, $status)
    {
        parent::terminate($input, $status); // TODO: Change the autogenerated stub
        // TODO this code is very dirty, 待優化
        $curlHitCount = new TerminateCurlHitCount();
        $curlHitCount->terminate($input, $status);
    }
}
