<?php

return [
    # 背景監控
    // 錯誤次數 Function
    'error_function' => 'absinthe:error_count:function',

    // 錯誤次數 狀態
    'error_status' => 'absinthe:error_count:status',

    // 錯誤次數 UserIP
    'error_ip' => 'absinthe:error_count:ip',

    // 錯誤次數 UserAgent
    'error_ua' => 'absinthe:error_count:ua',

    // Client資訊 IP
    'user_ip' => 'absinthe:user_info:ip',

    // Client資訊 UA
    'user_agent' => 'absinthe:user_info:agent',

    // API 觸發資訊
    'api_hit' => 'absinthe:api_hit',

    // Curl 觸發資訊
    'curl_hit' => 'absinthe:curl_hit',
];
