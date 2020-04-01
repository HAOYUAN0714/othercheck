<?php

namespace App\Listeners;

use App\Events\CatchException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class RecordErrorToRedis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CatchException $event
     * @return void
     */
    public function handle($event)
    {
        try {
            $redis = Redis::connection('core_master');
            $keyMap = config('reference.redis_key_mapping');
            $redis->hincrby($keyMap['error_function'], $event->request->route()->getActionMethod(), 1);
            $redis->hincrby($keyMap['error_status'], $event->errorCode, 1);
            $redis->hincrby($keyMap['error_ip'], $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 1);
            $redis->hincrby($keyMap['error_ua'], $_SERVER['HTTP_USER_AGENT'] ?? 'None', 1);
        } catch (\Exception $e) {
            \TelegramObject::sendMessage('Redis連線異常！');
        }
    }
}
