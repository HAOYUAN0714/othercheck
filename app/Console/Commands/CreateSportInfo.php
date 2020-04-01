<?php

namespace App\Console\Commands;

use App\Business\Sport;
use Illuminate\Console\Command;

class CreateSportInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-sport-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將球種名稱及對應ID存進資料庫';

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
     * @param Sport $sport
     * @return mixed
     */
    public function handle(Sport $sport)
    {
        try {

            $sport->saveSportInfo();

        } catch (\Exception $error) {   
            $msg = sprintf('系統排程錯誤 : [%s][%s] - %s'
            , $this->signature, $this->description, $error->getMessage());    
        
            $this->info($msg);

            # 記錄 Log
            \Log::channel('logforIMSportCore')->info($msg);
        }
    }
}
