<?php


namespace App\Repository;

use App\Model\Mapping\League_M;

class League
{
    protected $leagueMaster;

    public function __construct()
    {
        $this->leagueMaster = new League_M();
    }

    public function saveLeagueInfo($leagueInfo)
    {
        $connection = $this->leagueMaster;
        $chunks = array_chunk($leagueInfo, 200);
        foreach ($chunks as $chunk) {
//            $leagueId = [];
//            foreach ($chunk as $league) {
//                $leagueId[] = $league['league_id'];
//            }
//            $connection->updateOrInsert(['league_id' => 0], $chunk);
            $connection->insert($chunk);
        }
    }
}
