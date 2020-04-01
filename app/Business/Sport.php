<?php


namespace App\Business;

use App\Repository\IMSport as RepositoryIMSport;
use App\Repository\Sport as RepositorySport;


class Sport
{
    public function saveSportInfo()
    {
        $repoSport = new RepositorySport();
        $imSport = new RepositoryIMSport();

        $languageCode = ['ENG', 'CHS', 'JAP', 'KOR', 'TH', 'VN', 'ID'];

        $sportInfo = [];
        foreach ($languageCode as $langIndex => $langInfo) {
            $data = $imSport->getAllSportCount($langInfo, false);
            foreach ($data['SportCount'] as $sport) {
                if ($langIndex == 0) {
                    // 當外層迴圈跑第一次時，宣告一次 $sportInfo
                    $sportInfo[$sport['SportId']] = [
                        'sport_id' => $sport['SportId'],
                        'order_number' => $sport['OrderNumber']
                    ];
                }
                $sportInfo[$sport['SportId']]["sport_{$langInfo}_name"] = $sport['SportName'];
            }
        }
        
        $repoSport->saveSportInfo(array_values($sportInfo));
    }
}
