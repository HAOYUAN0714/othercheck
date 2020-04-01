<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class TerminateApiHitCount
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * 統計call API次數
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        date_default_timezone_set('Asia/Taipei');
        $routeName = \Route::currentRouteName();
        $key = config('reference.redis_key_mapping.api_hit') . ":{$routeName}";
        $execute = round(microtime(true) - LARAVEL_START, 3);

        $redis = Redis::connection('core_master');
        $clientIP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $hMGet = $redis->HMGET($key, ['total', $clientIP]);
        // 取出總計[0]、目前IP[1]
        $recordList = [];
        foreach ($hMGet as $index => $hGet) {
            $records = $hGet ? explode('|', $hGet) : [];
            foreach ($records as $record) {
                $exp = explode(':', $record);
                $recordList[$index][$exp[0]] = $exp[1];
            }
        }
        $totalCount = isset($recordList[0]['Count']) ? ++$recordList[0]['Count'] : 1;
        $count = isset($recordList[1]['Count']) ? ++$recordList[1]['Count'] : 1;
        $totalAverage = isset($recordList[0]['AVG']) ? (round(($recordList[0]['AVG'] + $execute) / 2, 3)) : $execute;
        $average = isset($recordList[1]['AVG']) ? (round(($recordList[1]['AVG'] + $execute) / 2, 3)) : $execute;
        $totalMax = isset($recordList[0]['MAX']) ? ($execute > $recordList[0]['MAX'] ? $execute : $recordList[0]['MAX']) : $execute;
        $max = isset($recordList[1]['MAX']) ? ($execute > $recordList[1]['MAX'] ? $execute : $recordList[1]['MAX']) : $execute;
        $totalMin = isset($recordList[0]['MIN']) ? ($execute < $recordList[0]['MIN'] ? $execute : $recordList[0]['MIN']) : $execute;
        $min = isset($recordList[1]['MIN']) ? ($execute < $recordList[1]['MIN'] ? $execute : $recordList[1]['MIN']) : $execute;
        $date = date('Y-m-d H:i:s');

        $totalValue = "Count:{$totalCount}|AVG:{$totalAverage}|MAX:{$totalMax}|MIN:{$totalMin}|Last:{$execute}|Updated:{$date}";
        $value = "Count:{$count}|AVG:{$average}|MAX:{$max}|MIN:{$min}|Last:{$execute}|Updated:{$date}";
        $redis->HMSET($key, ['total' => $totalValue, $clientIP => $value]);
    }
}
