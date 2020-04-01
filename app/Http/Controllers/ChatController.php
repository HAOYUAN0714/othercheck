<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getChatSocket(Request $request)
    {
        try {
            $env = $_SERVER['PROJECT_ENV'] ?? '';
            $urlMapping = [
                'gcp-qatest' => 'wss://msplay.vir999.net/gblive/fxLB',
                'gcp-master' => 'wss://msg.diablofr33.com/gblive/fxLB'
            ];

            return [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'socket_url' => $urlMapping[$env]
            ];
        } catch (\Exception $error) {
            return [
                'sys_result' => $error->getMessage(),
                'sys_code' => $error->getCode()
            ];
        }
    }
}
