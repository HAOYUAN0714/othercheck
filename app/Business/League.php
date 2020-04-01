<?php


namespace App\Business;

use App\Repository\IMSport as RepositoryIMSport;
use App\Repository\League as RepositoryLeague;


class League
{
    public function saveLeagueInfo()
    {
        $repoLeague = new RepositoryLeague();
        $imSport = new RepositoryIMSport();
        $data = $imSport->getLocalizations(51);

        $leagueInfo = [];
        foreach ($data['Localizations'][0]['Details'] as $league) {
            $leagueInfo[$league['Id']]['league_id'] = $league['Id'];
            foreach ($league['Names'] as $lang) {
                $leagueInfo[$league["Id"]]["league_{$lang['LanguageCode']}_name"] = $lang["Name"];
            }
        }
        $repoLeague->saveLeagueInfo($leagueInfo);
    }
}
