<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

// 客製體育
Route::get('/exsport/', function (Request $request) {

    // 防止爬蟲or機器人
    if (Browser::isBot()) {
        echo 'No need to wonder anymore!';
        exit;
    }

    // 解析URL
    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$host}:{$port}";

    // 確認廳主對應介面是否存在(若不存在則導回平台首頁)
    $projectEnv = $_SERVER['PROJECT_ENV'] ?? '';
    $hall = $request->input('hall') ?? $request->cookie('abs-hall');
    $view = config("reference.mapping_domain_to_view.{$projectEnv}.{$hall}");
    if (!$view) {
        App::abort(403, "{$url}");
    }

    // 通行驗證 (正式站、測試站)
    if ($view['verify'] AND !$request->cookie('abs-token')) {
        $timeStamp = $request->input('timeStamp');
        $token = $request->input('token');
        $languageCode = $request->input('LanguageCode');
        $memberCode = $request->input('memberCode');
        $key = '6c5ee68b1e90d70143d0cfd82d17a16e';
        $ck = $request->input('ck');
        $accessKey = md5($timeStamp . $token . $languageCode . $hall . $memberCode . $key);
        if ($accessKey !== $ck) {
            App::abort(404);
        }
    }

    // 判斷開啟版本
    if (Browser::isMobile() OR Browser::isTablet()) {
        return view($view['mobile']);
    } else {
        return view($view['default']);
    }
});

// 直播
Route::get('/exsport/live', function (Request $request) {

    // 防止爬蟲or機器人
    if (Browser::isBot()) {
        echo 'No need to wonder anymore!';
        exit;
    }

    // 解析URL
    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$host}:{$port}";

    // 確認廳主對應介面是否存在(若不存在則導回平台首頁)
    $projectEnv = $_SERVER['PROJECT_ENV'] ?? '';
    $hall = $request->input('hall') ?? $request->cookie('abs-hall');
    $view = config("reference.mapping_domain_to_live.{$projectEnv}.{$hall}");
    if (!$view) {
        App::abort(403, "{$url}");
    }

    // 通行驗證 (正式站、測試站)
    if ($view['verify'] AND !$request->cookie('abs-token')) {
        $timeStamp = $request->input('timeStamp');
        $token = $request->input('token');
        $languageCode = $request->input('LanguageCode');
        $memberCode = $request->input('memberCode');
        $key = '6c5ee68b1e90d70143d0cfd82d17a16e';
        $ck = $request->input('ck');
        $accessKey = md5($timeStamp . $token . $languageCode . $hall . $memberCode . $key);
        if ($accessKey !== $ck) {
            App::abort(404);
        }
    }

    // 判斷開啟版本
    if (Browser::isMobile() OR Browser::isTablet()) {
        return view($view['mobile']);
    } else {
        return view($view['default']);
    }
});

// 亞博直播後台
Route::get('/exsport/backstage', function (Request $request) {
    if ($request->query('secret') == substr(md5('backstage'), 10, 20)) {
        return view('C50EDA');
    }
    return abort(404);
});
