<?php

namespace App\Http\Middleware;

use Closure;

class TerminateCallApiLongTime
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * curl超時送Telegram提醒
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        $limit = $_SERVER['PROJECT_ENV'] == 'gcp-qatest' ? 10 : 1; // 測試站以 10 秒為標準
        $laraExecute = round(microtime(true) - LARAVEL_START, 3);

        if ($laraExecute >= $limit) {
            $curlExecute = round($_ENV['CURL_EXECUTE'] ?? 0, 3);
            $curlPercent = round($curlExecute / $laraExecute * 100, 1);
            $logExecute = round($_ENV['LOG_EXECUTE'] ?? 0, 3);
            $logPercent = round($logExecute / $laraExecute * 100, 1);
            $backendExecute = round($laraExecute - $curlExecute - $logExecute, 3);
            $backendPercent = round($backendExecute / $laraExecute * 100, 1);
            $routeName = \Route::currentRouteName();
            $req = json_encode($request->all());
            $clientIP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            \TelegramObject::sendMessage("💢端口回傳速度異常(>{$limit}s)：
            Function：{$routeName}
            Curl：{$curlExecute} s ... {$curlPercent} %
            Log：{$logExecute} s ... {$logPercent} %
            Backend：{$backendExecute} s ... {$backendPercent} %
            Total：{$laraExecute} s
            IP：{$clientIP}
            Request：{$req}");
        }
    }
}
