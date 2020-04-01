<?php

namespace App\Console\Commands;

use App\Business\IMSport;
use Illuminate\Console\Command;

class StoreBetTypeCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store-bet-type-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算玩法計數';

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
     * @param IMSport $IMSport
     * @return mixed
     */
    public function handle(IMSport $IMSport)
    {
        try {
            $start = microtime(true);
            $this->info('計算玩法計數...');

            $IMSport->storeBetTypeCountV2();

            $execute = microtime(true) - $start;
            $this->info("玩法計數完成 execute: {$execute}");
        } catch (\Exception $error) {
            $msg = sprintf('⚠️ 系統排程錯誤 : [%s][%s] - %s'
                , $this->signature, $this->description, $error->getMessage());

            $this->info($msg);

            # 送TG警報
            \TelegramObject::sendMessage($msg);
        }
    }
}
