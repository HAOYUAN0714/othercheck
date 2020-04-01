<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class MonitorErrorCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor-error-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '監控API錯誤次數';

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
     * @return mixed
     */
    public function handle()
    {
        $redis = Redis::connection('core_slave');
        $keyFunction = config('reference.redis_key_mapping.error_function');
        $keyStatus = config('reference.redis_key_mapping.error_status');
        $functionList = $redis->HGETALL($keyFunction);
        $statusList = $redis->HGETALL($keyStatus);
        $checkSend = false;
        $errorMsg = '❌ 過去1分鐘錯誤報告️' . PHP_EOL;
        foreach ($functionList as $funName => $count) {
            if ($count >= 10) {
                $checkSend = true;
            }
            $errorMsg = $errorMsg . "{$funName} 錯誤 {$count} 次" . PHP_EOL;
        }
        $statusMsg = '📊 錯誤狀態統計' . PHP_EOL;
        foreach ($statusList as $status => $count) {
            $msg = config("reference.exception.exception_list.{$status}")['error_msg'];
            $statusMsg = $statusMsg . "{$status} {$msg} => {$count}" . PHP_EOL;
        }

        if ($checkSend) \TelegramObject::sendMessage($errorMsg . $statusMsg); // 若無錯誤則不送訊息
        $redis->del($keyFunction, $keyStatus);
    }
}
