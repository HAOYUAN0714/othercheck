<?php

namespace App\Console\Commands;

use App\Business\League;
use Illuminate\Console\Command;

class CreateLeagueInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-league-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將索取定位的聯盟資訊存入DB';

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
     * @param League $league
     * @return mixed
     */
    public function handle(League $league)
    {
        try {

            $league->saveLeagueInfo();

        } catch (\Exception $error) {   
            $msg = sprintf('系統排程錯誤 : [%s][%s] - %s'
            , $this->signature, $this->description, $error->getMessage());    
        
            $this->info($msg);

            # 記錄 Log
            \Log::channel('logforIMSportCore')->info($msg);
        }
    }
}
