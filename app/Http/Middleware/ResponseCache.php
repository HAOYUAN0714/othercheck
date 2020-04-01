<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ResponseCache
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
        $env = $_ENV['APP_ENV'];
        $routeName = \Route::currentRouteName();
        // 快取時間 (若不在快取清單則為0)
        $ttl = config("reference.cache_list.{$env}.{$routeName}", 0);

        if ($ttl > 0) {
            // 檢查是否有快取, 有則直接回傳
            $req = $request->all();
            // 將Cookie加入命名, 防止語系、會員錯誤
            $cookie = [$request->cookie('abs-lang'), $request->cookie('abs-mem')];
            // 防止參數順序影響
            ksort($req);

            // cacheKey = md5(actionName + json(req+cookie))
            $cacheKey = md5($request->route()->getActionName() . json_encode([$req, $cookie]));
            if (Cache::has($cacheKey)) {
                return Response(Cache::get($cacheKey));
            }
        }
        return $next($request);
    }
}
