<?php
/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$env = $app->detectEnvironment(function () {
    // 取得 nginx 帶來的參數
    $projectEnv = $_SERVER['PROJECT_ENV'] ?? '';
    $serviceSite = $_SERVER['SERVICE_SITE'] ?? '';

    // 取得資料夾位置與設定檔名稱
    $folderPath = base_path("environment/project/{$projectEnv}");
    $fileName = "{$serviceSite}.env";
    $filePath = "{$folderPath}/{$fileName}";

    // 判斷設定檔案是否存在
    if (file_exists($filePath)) {
        $factoryInterface = new Dotenv\Environment\DotenvFactory();
        $loader = new Dotenv\Loader([$filePath], $factoryInterface, true);
        $dotenv = new Dotenv\Dotenv($loader);
        $dotenv->overload(); //this is important
    }
});
