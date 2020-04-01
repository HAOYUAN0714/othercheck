<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function testTelegramSendMessage()
    {
        try {
            $ip = $_SERVER['SERVER_ADDR'];
            $msg = "測試 Telegram SendMessage API 從 absinthe, IP:{$ip}";

            $response = \TelegramObject::sendMessage($msg);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'ret' => $response,
                'msg' => ''
            ];

            return $result;
        } catch (\Exception $error) {
            return [
                'sys_result' => 'Error',
                'sys_code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
        }
    }

    // 索取Client資訊
    public function checkInfo(Request $request)
    {
        $hash = substr(md5('rd1-engineer'), 0, 10);
        $apiKey = $request->cookie('Api-Key');
        if ($apiKey !== $hash) \App::abort(404);

        echo 'Host Name: ' . gethostname() . '<br>';
        echo 'Server Name: ' . $_SERVER['SERVER_NAME'] . '<br>';
        echo 'Server IP: ' . $_SERVER['SERVER_ADDR'] . '<br>';
        $currentDateTime = new \DateTime();
        $currentDateTime->setTimezone(new \DateTimeZone('Asia/Taipei'));
        $currentDateTime->setTimestamp($_SERVER['REQUEST_TIME']);
        echo 'Date Time: ' . $currentDateTime->format('Y-m-d H:i:s') . '<br>';
        echo 'Client IP: ' . (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : 'N/A') . '<br>';
        echo 'X_FORWARDED_FOR IP: ' . (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'N/A') . '<br>';
        echo 'X_FORWARDED IP: ' . (isset($_SERVER['HTTP_X_FORWARDED']) ? $_SERVER['HTTP_X_FORWARDED'] : 'N/A') . '<br>';
        echo 'FORWARDED_FOR IP: ' . (isset($_SERVER['HTTP_FORWARDED_FOR']) ? $_SERVER['HTTP_FORWARDED_FOR'] : 'N/A') . '<br>';
        echo 'HTTP_FORWARDED IP: ' . (isset($_SERVER['HTTP_FORWARDED']) ? $_SERVER['HTTP_FORWARDED'] : 'N/A') . '<br>';
        echo 'REMOTE_ADDR IP: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A') . '<br>';
        echo 'User Agent: ' . $_SERVER['HTTP_USER_AGENT'] . '<br>';
        echo 'X-Forwarded-Protocol: ' . (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'N/A') . '<br>';
    }
}
