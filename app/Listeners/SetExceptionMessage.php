<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetExceptionMessage
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
     * @param object $event
     * @return array
     */
    public function handle($event)
    {
        $errorCode = &$event->errorCode;
        $config = config('reference.exception.exception_list');

        // 產生專案格式錯誤代碼 (若設定檔未包含則設為 9000 未知錯誤)
        $errorCode = str_pad($errorCode, 9, '112030000', STR_PAD_LEFT);
        if (!in_array($errorCode, array_keys($config))) {
            $errorCode = '112039000';
        }
        $exceptList = $config[$errorCode];
        // 若無指定訊息則使用設定檔
        $errorMsg = $event->errorMsg != '' ? $event->errorMsg : $exceptList['error_msg'];
        $customMsg = "{$exceptList['custom_msg']} ({$errorCode})";

        return [
            'result' => $errorMsg,
            'code' => $errorCode,
            'customMsg' => $customMsg
        ];
    }
}
