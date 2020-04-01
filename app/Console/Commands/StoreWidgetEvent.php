<?php

namespace App\Console\Commands;

use App\Business\IMSport;
use Illuminate\Console\Command;

class StoreWidgetEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store-widget-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '存取Widget賽事清單背景';

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
    public function handle(IMSport $IMSport)
    {
        try {

            $IMSport->storeWidgetEventList();

        } catch (\Exception $error) {
            $msg = sprintf('⚠️ 系統排程錯誤 : [%s][%s] - %s'
            , $this->signature, $this->description, $error->getMessage());

            $this->info($msg);

            # 記錄 Log
            \Log::channel('logforIMSportCore')->info($msg);
        }
    }
}
