<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * |--------------------------------------------------------------------------
 * | *** 命名方式 ***
 * | 格式：{METHOD}_{Prefix}_{name}
 * |       (全大寫)_(大駝峰)_(蛇峰)
 * | e.g.：GET_AllSportCount or GET_LS_eventInfo
 * |--------------------------------------------------------------------------
 */

/*
|--------------------------------------------------------------------------
| 亞博直播
|--------------------------------------------------------------------------
*/

# 後台

// 索取賽事列表
Route::get('get-event-list', ['as' => 'GET_LS_event_list', 'uses' => 'LiveStreamController@getEventList']);

// 索取直播列表
Route::get('get-live-list', ['as' => 'GET_LS_live_list', 'uses' => 'LiveStreamController@getLiveList']);

// 手動對應直播
Route::put('correspond', ['as' => 'PUT_LS_correspond', 'uses' => 'LiveStreamController@correspond']);

// 取消對應
Route::put('cancel-correspond', ['as' => 'PUT_LS_cancel_correspond', 'uses' => 'LiveStreamController@cancelCorrespond']);

# 客端

// 索取球種賽事計數
Route::get('get-all-event-count', ['as' => 'GET_LS_all_event_count', 'uses' => 'LiveStreamController@getAllEventCount']);

// 索取頁數賽事資訊
Route::get('get-event-info-by-page', ['as' => 'GET_LS_event_by_page', 'uses' => 'LiveStreamController@getEventInfoByPage']);

// 索取滾球賽事資訊
Route::get('get-live-event-info', ['as' => 'GET_LS_live_event', 'uses' => 'LiveStreamController@getLiveEventInfo']);

// 索取指定賽事資訊
Route::get('get-selected-event-info', ['as' => 'GET_LS_selected_event', 'uses' => 'LiveStreamController@getSelectedEventInfo']);
