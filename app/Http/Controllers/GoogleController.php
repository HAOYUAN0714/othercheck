<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleController extends Controller
{
    // 特別給Google Health Check 使用
    public function getGoogleHealthCheck(Request $request)
    {
        # origin
        if (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match("/^(GoogleHC|a10hm|kube-probe)/", $_SERVER["HTTP_USER_AGENT"])) {
            header("HTTP/1.1 200 Ok");
            echo "ok";
            exit();
        }

        # Postman
        if (isset($_SERVER["HTTP_HTTP_USER_AGENT"]) && preg_match("/^(GoogleHC|a10hm|kube-probe)/", $_SERVER["HTTP_HTTP_USER_AGENT"])) {
            header("HTTP/1.1 200 Ok");
            echo "Postman ok!";
            exit();
        }

        header("HTTP/1.1 401 Unauthorized");
        echo "401 Unauthorized";

    }
}