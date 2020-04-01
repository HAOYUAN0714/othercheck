<?php

namespace App\Providers\TelegramObject;

class TelegramObject
{
    /**
     * Telegram SendMessage方法
     * @param $msg
     * @return mixed
     */
    public function sendMessage($msg = 'no msg')
    {
        $queryInput = [
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $msg,
        ];

        $telegram = \App::make('telegram');
        $botToken = env('TELEGRAM_BOT_TOKEN');

        $response = $telegram->get("/bot{$botToken}/sendMessage", $queryInput);

        return json_decode($response, true);
    }
}
