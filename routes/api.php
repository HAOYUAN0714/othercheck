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
 * | e.g.：GET_AllSportCount or GET_LS_event_info
 * |--------------------------------------------------------------------------
 */

/*
|--------------------------------------------------------------------------
| 官方API
|--------------------------------------------------------------------------
*/

// 索取所有體育計數
Route::post('get-all-sport-count', ['as' => 'POST_all_sport_count', 'uses' => 'IMSportController@getAllSportCount']);

// 索取所有競賽計數
Route::post('get-all-competition-count', ['as' => 'POST_all_competition_count', 'uses' => 'IMSportController@getAllCompetitionCount']);

// 索取賽事資料
Route::post('get-event-info', ['as' => 'POST_event_info', 'uses' => 'IMSportController@getEventInfo']);

// 索取DELTA賽事詳情
Route::post('get-delta-event-info', ['as' => 'POST_delta_event', 'uses' => 'IMSportController@getDeltaEventInfo']);

// 以頁數索取賽事資料
Route::post('get-event-info-by-page', ['as' => 'POST_event_by_page', 'uses' => 'IMSportController@getEventInfoByPage']);

// 索取滾球賽事資料
Route::post('get-live-event-info', ['as' => 'POST_live_event', 'uses' => 'IMSportController@getLiveEventInfo']);

// 索取賽事選項資料
Route::post('get-selected-event-info', ['as' => 'POST_selected_event', 'uses' => 'IMSportController@getSelectedEventInfo']);

// 索取熱門賽事
Route::post('get-popular-event', ['as' => 'POST_popular_event', 'uses' => 'IMSportController@getPopularEvent']);

// 索取優勝冠軍賽事
Route::post('get-outright-events', ['as' => 'POST_outright_event', 'uses' => 'IMSportController@getOutrightEvents']);

// 索取DELTA優勝冠軍賽事
Route::post('get-delta-outright-event-info', ['as' => 'POST_delta_outright', 'uses' => 'IMSportController@getDeltaOutrightEventInfo']);

// 搜索賽事
Route::post('search', ['as' => 'POST_search', 'uses' => 'IMSportController@search']);

// 索取定位
Route::post('get-localizations', ['as' => 'POST_localizations', 'uses' => 'IMSportController@getLocalizations']);

// 索取 DELTA 定位
Route::post('get-delta-localizations', ['as' => 'POST_delta_localizations', 'uses' => 'IMSportController@getDeltaLocalizations']);

// 索取完整赛果
Route::post('get-completed-results', ['as' => 'POST_completed_results', 'uses' => 'IMSportController@getCompletedResults']);

// 延遲操作會話
Route::post('extend-session', ['as' => 'POST_extend_session', 'uses' => 'IMSportController@extendSession']);

// 會員登出
Route::post('log-out', ['as' => 'POST_log_out', 'uses' => 'IMSportController@logOut']);

// 索取投注信息
Route::post('get-bet-info', ['as' => 'POST_bet_info', 'uses' => 'IMSportController@getBetInfo']);

// 投注
Route::post('place-bet', ['as' => 'POST_place_bet', 'uses' => 'IMSportController@placeBet']);

// 索取投注明细
Route::post('get-bet-list', ['as' => 'POST_bet_list', 'uses' => 'IMSportController@getBetList']);

// 索取投注帳目
Route::post('get-statement', ['as' => 'POST_statement', 'uses' => 'IMSportController@getStatement']);

// 取得會員餘額
Route::post('get-balance', ['as' => 'POST_balance', 'uses' => 'IMSportController@getBalance']);

// 索取待處理賭注狀態
Route::post('get-pending-wager-status', ['as' => 'POST_pending_wager', 'uses' => 'IMSportController@getPendingWagerStatus']);

// 索取通告
Route::post('get-announcement', ['as' => 'POST_announcement', 'uses' => 'IMSportController@getAnnouncement']);

// 索取用戶自定義
Route::post('get-user-preference', ['as' => 'POST_user_preference', 'uses' => 'IMSportController@getUserPreference']);

// 更新用户自定義
Route::post('update-user-preference', ['as' => 'POST_update_preference', 'uses' => 'IMSportController@updateUserPreference']);

// 索取收藏賽事
Route::post('get-favourite-event', ['as' => 'POST_favourite_event', 'uses' => 'IMSportController@getFavouriteEvent']);

// 新增收藏賽事
Route::post('add-favourite-event', ['as' => 'POST_add_favourite', 'uses' => 'IMSportController@addFavouriteEvent']);

// 刪除收藏賽事
Route::post('remove-favourite-event', ['as' => 'POST_remove_favourite', 'uses' => 'IMSportController@removeFavouriteEvent']);

// 索取下注紀錄
Route::post('get-bet-trade', ['as' => 'POST_bet_trade', 'uses' => 'IMSportController@getBetTrade']);

// 提交回購
Route::post('submit-buy-back', ['as' => 'POST_buy_back', 'uses' => 'IMSportController@submitBuyBack']);

// 索取服務器時間
Route::post('get-server-time', ['as' => 'POST_server_time', 'uses' => 'IMSportController@getServerTime']);


/*
|--------------------------------------------------------------------------
| 我方API
|--------------------------------------------------------------------------
*/

## 聊天室

// 檢索賽事是否存在
Route::post('post-exist-event', ['as' => 'POST_exist_event', 'uses' => 'IMSportController@postExistEvent']);

## 必博

// 索取Widget動畫賽事清單
Route::post('get-widget-event-list', ['as' => 'POST_widget_event_list', 'uses' => 'IMSportController@getWidgetEventList']);

// 索取所有賽事計數
Route::post('get-all-event-count', ['as' => 'POST_all_event_count', 'uses' => 'IMSportController@getAllEventCount']);

// 索取聊天室網址
Route::get('get-chat-socket', ['as' => 'GET_chat_socket', 'uses' => 'ChatController@getChatSocket']);


## 威尼斯人

// 索取參賽表計數
Route::post('get-event-count', ['as' => 'POST_event_count', 'uses' => 'IMSportController@getEventCount']);

// 索取國家聯盟賽事清單
Route::post('get-event-list', ['as' => 'POST_event_list', 'uses' => 'IMSportController@getEventList']);

// 索取冠軍聯盟賽事清單
Route::post('get-outright-event-list', ['as' => 'POST_outright_event_list', 'uses' => 'IMSportController@getOutrightEventList']);

// 索取指定聯盟下的冠軍賽事
Route::post('get-league-outright-event', ['as' => 'POST_league_outright_event', 'uses' => 'IMSportController@getLeagueOutrightEvent']);

// 索取網頁球種與玩法的計數
Route::post('get-bettype-count', ['as' => 'POST_bet_type_count', 'uses' => 'IMSportController@getBetTypeCount']);

// 索取首頁資訊
Route::post('get-home-info', ['as' => 'POST_home_info', 'uses' => 'IMSportController@getHomeInfo']);

// 索取現有賽事體育清單
Route::post('get-sport-mapping', ['as' => 'POST_sport_mapping', 'uses' => 'IMSportController@getSportMapping']);

// 索取Widget動畫
Route::get('get-widget-loader', ['as' => 'GET_widget_loader', 'uses' => 'IMSportController@getWidgetLoader']);

// 索取所有日期(早餐+今日)的賽事
Route::post('get-event-info-all-market', ['as' => 'POST_event_all_market', 'uses' => 'IMSportController@getEventInfoAllMarket']);

## 測試用

// 測試用test
Route::get('get-test', ['as' => 'GET_test', 'uses' => 'IMSportController@getTest']);


/*
|--------------------------------------------------------------------------
| 其他
|--------------------------------------------------------------------------
*/
## Google Health Check
Route::get('google-health-check', ['as' => 'GET_google_health_check', 'uses' => 'GoogleController@getGoogleHealthCheck']);

## 索取Client資訊
Route::get('check-info', ['as' => 'GET_check_info', 'uses' => 'SystemController@checkInfo']);

## Telegram

// 測試發送訊息
Route::get('test-send-telegram', ['as' => 'GET_test_send_telegram', 'uses' => 'SystemController@testTelegramSendMessage']);
