<?php
/**
 * 快取清單
 * 需要快取的 環境 / 路由名稱 / 秒數
 */

return [
    'master' => [
        'GET_LS_all_event_count' => 10,
        'GET_LS_event_by_page' => 10,
        'GET_LS_live_event' => 5,
        'GET_LS_selected_event' => 10,
        'POST_all_sport_count' => 10,
        'POST_completed_results' => 10,
        'POST_announcement' => 5,
        'POST_all_event_count' => 10,
        'POST_sport_mapping' => 10
    ],
    'qatest' => [
        'GET_LS_all_event_count' => 180,
        'GET_LS_event_by_page' => 180,
        'GET_LS_live_event' => 180,
        'GET_LS_selected_event' => 180,
        'POST_all_sport_count' => 180,
        'POST_event_info' => 180,
        'POST_event_by_page' => 180,
        'POST_live_event' => 180,
        'POST_selected_event' => 180,
        'POST_outright_event' => 180,
        'POST_completed_results' => 180,
        'POST_announcement' => 180,
        'POST_all_event_count' => 180,
        'POST_league_outright_event' => 180,
        'POST_sport_mapping' => 180,
        'POST_event_all_market' => 180
    ],
    'develop' => [
        'GET_LS_all_event_count' => 180,
        'GET_LS_event_by_page' => 180,
        'GET_LS_live_event' => 180,
        'GET_LS_selected_event' => 180,
        'POST_all_sport_count' => 180,
        'POST_event_info' => 180,
        'POST_event_by_page' => 180,
        'POST_live_event' => 180,
        'POST_selected_event' => 180,
        'POST_outright_event' => 180,
        'POST_completed_results' => 180,
        'POST_announcement' => 180,
        'POST_all_event_count' => 180,
        'POST_league_outright_event' => 180,
        'POST_sport_mapping' => 180,
        'POST_event_all_market' => 180
    ],
    'local' => [
        'GET_LS_all_event_count' => 10,
        'GET_LS_event_by_page' => 10,
        'GET_LS_live_event' => 5,
        'GET_LS_selected_event' => 10,
        'POST_all_sport_count' => 10,
        'POST_completed_results' => 10,
        'POST_announcement' => 5,
        'POST_all_event_count' => 10,
        'POST_sport_mapping' => 10
    ]
];
