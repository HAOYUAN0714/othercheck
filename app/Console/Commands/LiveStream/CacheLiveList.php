<?php

namespace App\Console\Commands\LiveStream;

use App\Business\LiveStream;
use Illuminate\Console\Command;

class CacheLiveList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache-live-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快取直播列表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param LiveStream $liveStream
     * @return mixed
     */
    public function handle(LiveStream $liveStream)
    {
        try {
            $start = microtime(true);
            $this->info('快取直播列表中...');

            $liveStream->cacheLiveList();

            $execute = microtime(true) - $start;
            $this->info("快取直播完成 execute: {$execute}");
        } catch (\Exception $error) {
            $msg = sprintf('⚠️ 系統排程錯誤 : [%s][%s] - %s'
                , $this->signature, $this->description, $error->getMessage());

            $this->info($msg);

            # 送TG警報
            \TelegramObject::sendMessage($msg);
        }
    }
}
