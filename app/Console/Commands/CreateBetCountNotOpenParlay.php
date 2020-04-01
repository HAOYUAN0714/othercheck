<?php

namespace App\Console\Commands;

use App\Business\IMSport;
use Illuminate\Console\Command;

class CreateBetCountNotOpenParlay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-betCount-not-openParlay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將網頁版的非串關計數資料存進資料庫';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $start = microtime(true);
            date_default_timezone_set('Asia/Taipei');
            $time = date('Y-m-d H:i:s');
            $msg = "time:{$time} env:{$_ENV['APP_ENV']} name:{$this->name}";
            \TelegramObject::sendMessage($msg);
            $this->info('準備寫進資料庫中...');
//            $imSport = new IMSport();
//
//            $imSport->storeBettypeCount(1);
            $start2 = microtime(true);
            $execute1 = $start2 - $start;
            $this->info("今日的資料已成功建立 execute: {$execute1}");

//            $imSport->storeBettypeCount(2);
            $execute2 = microtime(true) - $start2;
            $this->info("早盤的資料已成功建立 execute: {$execute2}");

        } catch (\Exception $error) {
            $msg = sprintf('⚠️ 系統排程錯誤 : [%s][%s] - %s'
                , $this->signature, $this->description, $error->getMessage());

            $this->info($msg);

            # 記錄 Log
            \Log::channel('logforBetTypeCount')->info($msg);
        }
    }
}
