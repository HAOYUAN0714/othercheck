<?php


namespace App\Repository;

use App\Model\Mapping\Sport_M;

class Sport
{
    protected $sportMaster;

    public function __construct()
    {
        $this->sportMaster = new Sport_M();
    }

    public function saveSportInfo($sportInfo)
    {
        $connection = $this->sportMaster;
        foreach ($sportInfo as $sport) {
            if($connection->where('sport_id', $sport['sport_id'])->count() == 0) {
                // 資料庫中沒有sport_id的才insert
                $connection->insert($sport);
            } else{
                // 資料庫中有sport_id的做更新
                $connection->where('sport_id', $sport['sport_id'])
                    ->update($sport);
            }
        }
    }
}
