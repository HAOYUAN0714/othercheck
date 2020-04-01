<?php


namespace App\Business;


use App\Repository\CronJob\WidgetEvent as RepositoryWidgetEvent;
use App\Repository\IMSport as RepositoryIMSport;
use App\Repository\IMSportDB as IMSportDB;
use Illuminate\Support\Arr;

class IMSport
{
    public function postExistEvent($eventIds)
    {
        $imSport = new RepositoryIMSport();
        $sportCount = $imSport->getAllSportCount('ENG', false);

        if (!$sportCount) {
            throw new \Exception('API-AllSportCount 回传为空', 112030000);
        }

        # 取得球種清單
        $sportList = array_map(function ($sport) {
            return $sport['SportId'];
        }, $sportCount['SportCount']);

        # 設置回傳格式
        $result['ExistEvent'] = [];
        foreach ($eventIds as $eventId) {
            $result['ExistEvent'][$eventId] = false;
        }

        # 遍歷所有球種搜尋賽事
        foreach ($sportList as $sportId) {
            if (!$eventIds) break; // 查詢完畢
            $eventList = $imSport->getSelectedEventInfo($sportId, $eventIds, 1,
                false, false, 'ENG', [-1]);
            if ($eventList['StatusCode'] != 380 AND $eventList['Sports']) { // 判斷賽事清單不為空或380
                foreach ($eventList['Sports'][0]['Events'] as $event) {
                    $result['ExistEvent'][$event['EventId']] = true;
                    unset($eventIds[array_search($event['EventId'], $eventIds)]); // 若找到賽事則由待查清單中刪除
                    $eventIds = array_values($eventIds); // 重置索引
                }
            }
        }
        return $result;
    }

    public function getEventCount($languageCode, $sportId, $isCombo)
    {
        date_default_timezone_set('GMT');
        $eventDate = date("Y-m-d", strtotime('1 day -4 hour'));

        $imSport = new RepositoryIMSport();

        $today = $imSport->getAllSportCount($languageCode, $isCombo);

        if (!$today) {
            throw new \Exception('API-AllSportCount 回传为空', 112030000);
        }

        $todayCount = 0;
        foreach ($today['SportCount'] as $sport) {
            if ($sport['SportId'] == $sportId) {
                $todayCount += $sport['TodayFECount'];
                break;
            }
        }

        $tomorrow = $imSport->getAllCompetitionCount(
            $languageCode,
            $sportId,
            $isCombo,
            1,
            false,
            $eventDate
        );

        if (!$tomorrow) {
            throw new \Exception('API-AllCompetitionCount 回传为空', 112030000);
        }

        $tomorrowCount = 0;
        foreach ($tomorrow['CompetitionCount'] as $league) {
            $tomorrowCount += $league['Count'];
        }

        return [
            'SportId' => $sportId,
            'IsCombo' => $isCombo,
            'TodayCount' => $todayCount,
            'TomorrowDate' => $eventDate,
            'TomorrowCount' => $tomorrowCount
        ];
    }

    public function getEventList($languageCode, $sportId, $isCombo, $eventDate, $Key)
    {
        $startMarket = 1;
        $totalMarket = 2;

        date_default_timezone_set('GMT');

        switch ($Key) { // null=今日+早餐(所有)
            case 1: // 早餐
                $totalMarket = 1;
                break;
            case 2: // 今日(GMT-4)
                $startMarket = 2;
                break;
            case 3: // 首頁索取今日前三熱門
                $startMarket = 2;
                break;
        }

        $imSport = new RepositoryIMSport();

        $dateInfo = [];
        $countryList = [];
        $leagueList = [];
        for ($market = $startMarket; $market <= $totalMarket; $market++) {
            $result = $imSport->getAllCompetitionCount(
                $languageCode,
                $sportId,
                $isCombo,
                $market,
                false,
                $eventDate
            );

            if (!$result) {
                throw new \Exception('API-AllCompetitionCount 回传为空', 112030000);
            }

            foreach ($result['CompetitionCount'] as $league) {
                // 判斷國家是否建立(init)
                if (!in_array($league['ProgrammeId'], array_keys($countryList))) {
                    $countryList[$league['ProgrammeId']] = [
                        'ProgrammeId' => $league['ProgrammeId'],
                        'ProgrammeName' => $league['ProgrammeName'],
                        'ProgrammeOrderNumber' => $league['ProgrammeOrderNumber'],
                    ];
                }
                // 判斷是否有重複聯盟 (早餐+今日會出現) //TODO 缺指定聯盟返回早餐＋今日賽事
                if (!$Key & in_array($league['CompetitionId'], array_keys($leagueList))) {
                    $countryList[$leagueList[$league['CompetitionId']]]['league'][$league['CompetitionId']]['Count'] += $league['Count'];
                } else {
                    $leagueList[$league['CompetitionId']] = $league['ProgrammeId'];
                    $countryList[$league['ProgrammeId']]['league'][$league['CompetitionId']] = $league; // 將聯盟放入對應國家
                }
            }
        }

        //取出前三熱門
        if ($Key == 3) {
            return array_slice($countryList, 0, 3);
        }

        $popular = array_slice($countryList, 0, 5); //取出前五熱門
        $countryList = array_slice($countryList, 5);
        $countryList = collect($countryList)->sortBy('ProgrammeName')->toArray();

        //當週日期
        for ($i = 1; $i <= 7; $i++) {
            $dateInfo[] = date("Y-m-d", strtotime($i . ' day -4 hour'));
        }

        return [
            'dateList' => $dateInfo,
            'popular' => array_values($popular),
            'otherList' => array_values($countryList)
        ];
    }

    public function getOutrightEventList($languageCode, $sportId, $isCombo)
    {
        $imSport = new RepositoryIMSport();
        $outRightList = $imSport->getOutrightEvents($sportId, $languageCode, $isCombo);

        if (!$outRightList) {
            throw new \Exception('API-OutrightEvents 回传为空', 112030000);
        }

        $leagueList['league'] = [];
        foreach ($outRightList['Events'] as $outRight) {
            // 判斷聯盟是否建立(init)
            if (!in_array($outRight['Competition']['CompetitionId'], array_keys($leagueList['league']))) {
                $leagueList['league'][$outRight['Competition']['CompetitionId']] = [
                    'SportId' => $sportId,
                    'CompetitionId' => $outRight['Competition']['CompetitionId'],
                    'CompetitionName' => $outRight['Competition']['CompetitionName'],
                    'Count' => 0
                ];
            }
            $leagueList['league'][$outRight['Competition']['CompetitionId']]['Count']++;
        }

        return $leagueList;
    }

    public function getLeagueOutrightEvent($sportId, $languageCode, $competitionId, $competitionIds, $isCombo)
    {
        $imSport = new RepositoryIMSport();
        $outrightEvents = $imSport->getOutrightEvents($sportId, $languageCode, $isCombo);

        if (!$outrightEvents) {
            throw new \Exception('API-OutrightEvents 回传为空', 112030000);
        }

        $eventList = [
            'SportId' => $sportId,
            'IsCombo' => $isCombo,
            'CompetitionId' => $competitionIds ?? $competitionId,
            'Events' => []
        ];

        if (!$competitionId and !$competitionIds) {
            $eventList['Events'] = $outrightEvents['Events'];
            return $eventList;
        }

        foreach ($outrightEvents['Events'] as $event) {
            if ($competitionIds and in_array($event['Competition']['CompetitionId'], $competitionIds)) {
                $eventList['Events'][] = $event;
            } elseif ($event['Competition']['CompetitionId'] == $competitionId) {
                $eventList['Events'][] = $event;
            }
        }
        if ($competitionId) {
            $eventList['SportMapping'] = $this->getSportMapping($languageCode, $isCombo);
        }

        return $eventList;
    }

    public function getHomeInfo($languageCode, $isCombo)
    {
        $imSport = new RepositoryIMSport();
        $sportCount = $imSport->getAllSportCount($languageCode, $isCombo);

        if (!$sportCount) {
            throw new \Exception('API-AllSportCount 回传为空', 112030000);
        }

        $homeInfo = [
            'sportMapping' => [],   // sportId 對應體育項目
            'live' => [             // 滾球數量及前三聯盟資訊
                'all' => 0,
            ],
            'today' => []   // 今日
        ];

        foreach ($sportCount['SportCount'] as $sport) {
            if ($sport['Count']) {
                $homeInfo['sportMapping'][$sport['SportId']] = [
                    'sportName' => $sport['SportName'],
                    'orderNumber' => $sport['OrderNumber']
                ];
            }
            if ($sport['IsHasLive']) {
                $homeInfo['live']['all'] += $sport['RBFECount'];
                $homeInfo['live'][$sport['SportId']] = $sport['RBFECount'];
            }
            if ($sport['TodayFECount']) {
                $homeInfo['today'][$sport['SportId']] = [
                    'all' => $sport['TodayFECount']
                ];
            }
        }

        $sportIds = array_keys($homeInfo['today']);
        foreach ($sportIds as $sportId) {
            $countryList = $this->getEventList($languageCode, $sportId, $isCombo, null, 3);
            $rankNum = 1;
            foreach ($countryList as $country) {
                $homeInfo['today'][$sportId]['rank'][$rankNum] = [
                    'CompetitionId' => Arr::first($country['league'])['CompetitionId'],
                    'CompetitionName' => Arr::first($country['league'])['CompetitionName'],
                    'Count' => Arr::first($country['league'])['Count']
                ];
                $rankNum++;
            }
        }

        return $homeInfo;
    }

    public function getSportMapping($languageCode, $isCombo, $isLive = null, $market = null)
    {
        $imSport = new RepositoryIMSport();

        $sportCount = $imSport->getAllSportCount($languageCode, $isCombo);

        if (!$sportCount) {
            throw new \Exception('API-AllSportCount 回传为空', 112030000);
        }

        $sportMapping['sportMapping'] = [];
        foreach ($sportCount['SportCount'] as $sport) {
            if ($market) { // 針對早餐or今日 TODO 待優化
                if ($market == 1 ? ($sport['EarlyFECount']) : ($sport['TodayFECount'])) {
                    $sportMapping['sportMapping'][$sport['SportId']] = [
                        'sportName' => $sport['SportName'],
                        'orderNumber' => $sport['OrderNumber']
                    ];
                }
                // 針對滾球or所有盤口
            } elseif ($isLive ? ($sport['IsHasLive']) : ($sport['Count'])) {
                $sportMapping['sportMapping'][$sport['SportId']] = [
                    'sportName' => $sport['SportName'],
                    'orderNumber' => $sport['OrderNumber']
                ];
                if ($isLive) {
                    $sportMapping['sportMapping'][$sport['SportId']]['liveCount'] = $sport['RBFECount'];
                }
            }
        }
        return $sportMapping;
    }

    public function storeWidgetEventList()
    {
        $imSport = new RepositoryIMSport();

        date_default_timezone_set('GMT');
        $langList = ['ENG', 'CHS', 'JAP', 'KOR', 'TH', 'VN', 'ID']; // 語系列表
        $eventList['cron_date'] = date("Y-m-d", strtotime('-4 hour'));
        foreach ($langList as $languageCode) {
            for ($sportId = 1; $sportId <= 2; $sportId++) {
                $page = 1;
                $leagueInfo = [];
                do {
                    $eventInfo = $imSport->getEventInfoByPage($sportId, 2, 3, false, $page,
                        $languageCode, null, [-1]);

                    foreach ($eventInfo['Sports'][0]['Events'] ?? [] as $event) {
                        if ($event['HasVisualization']) { // 判斷賽事是否提供Widget
                            $league = $event['Competition']; // 聯盟
                            // 將同聯盟的賽事放在一起
                            $leagueInfo[$league['CompetitionId']]['CompetitionName'] = $league['CompetitionName'];
                            $leagueInfo[$league['CompetitionId']]['Events'][] = [
                                'EventDate' => $event['EventDate'],
                                'HomeTeam' => $event['HomeTeam'],
                                'AwayTeam' => $event['AwayTeam']
                            ];
                        }
                    }

                    $page++;
                    $pageSize = $eventInfo['PageSize'];
                } while ($page <= $pageSize);

                $eventList['lang_' . $languageCode][$sportId] = [
                    'SportId' => $sportId,
                    'SportName' => $eventInfo['Sports'][0]['SportName'] ?? null,
                    'Competitions' => $leagueInfo
                ];
            }
            $eventList['lang_' . $languageCode] = json_encode($eventList['lang_' . $languageCode]);
        }
        $widgetEvent = new RepositoryWidgetEvent();
        $widgetEvent->storeWidgetEventList($eventList);
    }

    public function getWidgetEventList($languageCode)
    {
        $widgetEvent = new RepositoryWidgetEvent();
        $eventList = $widgetEvent->getWidgetEventList($languageCode);

        if (!$eventList) {
            throw new \Exception('资料库无当日Widget赛事资料', 112033003);
        }

        return ['EventList' => $eventList['lang_' . $languageCode]];
    }

    public function getAllEventCount($languageCode, $token = null, $memberCode = null)
    {
        $imSport = new RepositoryIMSport();
        $comboList = [false, true];

        $eventCount = [
            'Today' => [
                'TotalCount' => 0,
                'Sport' => []
            ], 'Early' => [
                'TotalCount' => 0,
                'Sport' => []
            ], 'Combo' => [
                'TotalCount' => 0,
                'Sport' => []
            ], 'Favourite' => [
                'TotalCount' => 0,
                'Sport' => []
            ], 'Outright' => [
                'TotalCount' => 0,
                'Sport' => []
            ]
        ];
        foreach ($comboList as $combo) {
            $sportCount = $imSport->getAllSportCount($languageCode, $combo);
            if (!$sportCount) {
                throw new \Exception('API-AllSportCount 回传为空', 112030000);
            }
            foreach ($sportCount['SportCount'] as $sport) {
                if (!$combo) { // 非連串
                    $todayCount = $sport['TodayFECount'] + $sport['RBFECount']; // 今日包含滾球中
                    if ($todayCount !== 0) {
                        $eventCount['Today']['Sport'][] = [
                            'SportId' => $sport['SportId'],
                            'SportName' => $sport['SportName'],
                            'OrderNumber' => $sport['OrderNumber'],
                            'TodayCount' => $todayCount
                        ];
                        $eventCount['Today']['TotalCount'] += $todayCount;
                    }
                    $earlyCount = $sport['EarlyFECount'];
                    if ($earlyCount !== 0) {
                        $eventCount['Early']['Sport'][] = [
                            'SportId' => $sport['SportId'],
                            'SportName' => $sport['SportName'],
                            'OrderNumber' => $sport['OrderNumber'],
                            'EarlyCount' => $earlyCount
                        ];
                        $eventCount['Early']['TotalCount'] += $earlyCount;
                    }
                    $outrightCount = $sport['ORCount'];
                    if ($outrightCount !== 0) {
                        $eventCount['Outright']['Sport'][] = [
                            'SportId' => $sport['SportId'],
                            'SportName' => $sport['SportName'],
                            'OrderNumber' => $sport['OrderNumber'],
                            'OutrightCount' => $outrightCount
                        ];
                        $eventCount['Outright']['TotalCount'] += $outrightCount;
                    }
                } else { // 連串過關
                    $comboCount = $sport['TodayFECount'] + $sport['RBFECount'] + $sport['EarlyFECount']; // 今日包含滾球中
                    if ($comboCount !== 0) {
                        $eventCount['Combo']['Sport'][] = [
                            'SportId' => $sport['SportId'],
                            'SportName' => $sport['SportName'],
                            'OrderNumber' => $sport['OrderNumber'],
                            'ComboCount' => $comboCount
                        ];
                        $eventCount['Combo']['TotalCount'] += $comboCount;
                    }
                }
            }
        }

        $favourite = $imSport->getFavouriteEvent(3, $memberCode, $token, null, [-1],
            null, null, 2, $languageCode);
        if (!$favourite) {
            throw new \Exception('API-FavouriteEvent 回传为空', 112030000);
        }
        foreach ($favourite['Sports'] ?? [] as $sport) {
            $favCount = count($sport['Events']);
            $eventCount['Favourite']['Sport'][] = [
                'SportId' => $sport['SportId'],
                'SportName' => $sport['SportName'],
                'OrderNumber' => $sport['OrderNumber'],
                'FavCount' => $favCount
            ];
            $eventCount['Favourite']['TotalCount'] += $favCount;
        }
        return $eventCount;
    }

    // 索取玩法的賽事計數(PC)
    public function getBettypeCount($key)
    {
        $betCount = new IMSportDB();

        date_default_timezone_set('GMT');
        $date = date("Y-m-d", strtotime('-4 hour')) . '-' . $key;
        $response = $betCount->getBettypeCount($date);

        if (!$response) {
            throw new \Exception('资料库无赛事计数资料', 112033003);
        }
        return $response;
    }

    // 儲存玩法的賽事計數
    public function storeBetTypeCountV2()
    {
        $betTypeCount = [];
        $keyList = [
            1 => 'TodayFECount',
            2 => 'EarlyFECount',
            3 => 'ComboFECount'
        ];
        $imSport = new RepositoryIMSport();

        // 取得所有球種串關數量
        $allCombo = $imSport->getAllSportCount('chs', true);
        if ($allCombo['StatusCode'] != 100) {
            throw new \Exception("端口資料異常, msg={$allCombo['StatusDesc']}", 112033005);
        }

        $allComboCount = [];
        foreach ($allCombo['SportCount'] as $sport) {
            $allComboCount[$sport['SportId']] = $sport['Count'];
        }

        // 取得所有球種資訊
        $allSports = $imSport->getAllSportCount('chs', false);
        if ($allSports['StatusCode'] != 100) {
            throw new \Exception("端口資料異常, msg={$allSports['StatusDesc']}", 112033005);
        }

        $sportData = []; # 球種的計數資訊
        foreach ($allSports['SportCount'] as $sport) {
            $sportId = $sport['SportId'];
            $betTypeIds = $sportId == 1 ? [1, 2, 3, 5, 6, 7, 9] : [1]; # 主要玩法ID (足球外只取讓球)
            $earlyCount = $todayCount = $comboCount = []; # 早餐 / 今日 / 串關 有開玩法的賽事計數

            // 若球種有早盤賽事, 取賽事資訊
            if ($sport['EarlyFECount']) {
                $earlyEvent = $imSport->getEventInfo($sportId, 1, $betTypeIds);
                if ($earlyEvent['StatusCode'] != 100) {
                    throw new \Exception("端口資料異常, msg={$earlyEvent['StatusDesc']}", 112033005);
                }
                // 遍歷所有賽事, 統計玩法的賽事數量
                foreach ($earlyEvent['Sports'][0]['Events'] ?? [] as $event) {
                    $betTypes = collect($event['MarketLines'])->pluck('BetTypeId')->unique();
                    foreach ($betTypes as $betType) {
                        $earlyCount[$betType] = isset($earlyCount[$betType]) ? ++$earlyCount[$betType] : 1;
                        // 計算串關數量
                        if ($event['OpenParlay']) {
                            $comboCount[$betType] = isset($comboCount[$betType]) ? ++$comboCount[$betType] : 1;
                        }
                    }
                }
            }

            // 若球種有今日賽事, 取賽事資訊
            if ($sport['TodayFECount']) {
                $todayEvent = $imSport->getEventInfo($sportId, 2, $betTypeIds);
                if ($todayEvent['StatusCode'] != 100) {
                    throw new \Exception("端口資料異常, msg={$todayEvent['StatusDesc']}", 112033005);
                }
                // 遍歷所有賽事, 統計玩法的賽事數量
                foreach ($todayEvent['Sports'][0]['Events'] ?? [] as $event) {
                    $betTypes = collect($event['MarketLines'])->pluck('BetTypeId')->unique();
                    foreach ($betTypes as $betType) {
                        $todayCount[$betType] = isset($todayCount[$betType]) ? ++$todayCount[$betType] : 1;
                        // 計算串關數量
                        if ($event['OpenParlay']) {
                            $comboCount[$betType] = isset($comboCount[$betType]) ? ++$comboCount[$betType] : 1;
                        }
                    }
                }
            }

            // 若球種有滾球賽事, 取賽事資訊
            if ($sport['RBFECount']) {
                $liveEvent = $imSport->getEventInfo($sportId, 3, $betTypeIds);
                if ($liveEvent['StatusCode'] != 100) {
                    throw new \Exception("端口資料異常, msg={$liveEvent['StatusDesc']}", 112033005);
                }
                // 遍歷所有賽事, 統計玩法的賽事數量 (滾球加入今日)
                foreach ($liveEvent['Sports'][0]['Events'] ?? [] as $event) {
                    $betTypes = collect($event['MarketLines'])->pluck('BetTypeId')->unique();
                    foreach ($betTypes as $betType) {
                        $todayCount[$betType] = isset($todayCount[$betType]) ? ++$todayCount[$betType] : 1;
                        // 計算串關數量
                        if ($event['OpenParlay']) {
                            $comboCount[$betType] = isset($comboCount[$betType]) ? ++$comboCount[$betType] : 1;
                        }
                    }
                }
            }
            $countList = [
                'TodayFECount' => $todayCount,
                'EarlyFECount' => $earlyCount,
                'ComboFECount' => $comboCount
            ];
            // 合計各盤口的賽事數量
            $betType = [];
            foreach ($countList as $key => $marketCount) {
                foreach ($betTypeIds as $betTypeId) {
                    $count = $marketCount[$betTypeId] ?? 0;
                    // 足球的 讓球 & 大小 放在一起, 取最大
                    if ($betTypeId == 2) {
                        $betType[$key][1]['count'] = $count > $betType[$key][1]['count'] ? $count : $betType[$key][1]['count'];
                        continue;
                    }
                    $betType[$key][$betTypeId] = [
                        'count' => $count,
                        'betTypeId' => $betTypeId
                    ];
                }
                // 插入冠軍計數 (串關無冠軍)
                $betType[$key][11] = [
                    'count' => $key == 'ComboFECount' ? 0 : $sport['ORCount'],
                    'betTypeId' => 11
                ];
            }
            // 是否為主要球種
            $mainOrRest = in_array($sportId, [1, 2, 3, 8, 19]) ? 'sportMain' : 'sportRest';
            foreach ($keyList as $key => $keyName) {
                switch ($key) {
                    case 1: # 今日=今日+滾球+冠軍
                        $count = $sport[$keyName] + $sport['RBFECount'] + $sport['ORCount'];
                        break;
                    case 2: # 早盤+冠軍
                        $count = $sport[$keyName] + $sport['ORCount'];
                        break;
                    case 3: # 串關=所有-冠軍(不支持串關)
                        $count = $allComboCount[$sportId] ?? 0;
                        break;
                    default:
                        $count = 0;
                }
                // 球種的計數資訊
                $sportData[$keyName][$mainOrRest][] = [
                    'count' => $count,
                    'sportId' => $sportId,
                    'betTypes' => $betType[$keyName]
                ];
            }
        }
        foreach ($keyList as $key => $keyName) {
            $betTypeCount[] = [
                'name' => '123',
                'count_key' => $key,
                'data' => json_encode($sportData[$keyName])
            ];
        }
        $imSportDB = new IMSportDB();
        $imSportDB->storeBetTypeCountV2($betTypeCount);
    }

    // 取得玩法的賽事計數
    public function getBetTypeCountV2($key)
    {
        $imSportDB = new IMSportDB();
        $betTypeCount = $imSportDB->getBetTypeCountV2($key);

        return json_decode($betTypeCount, true);
    }

    // 儲存玩法的賽事計數進DB(PC)
    public function storeBettypeCount($Key)
    {
        $betCount = new IMSportDB();

        $sportMain = [];   // 存放前五個主要球種(足球, 籃球, 網球, 棒球, 美式足球)
        $sportRest = [];   // 存放剩餘的球種

        // 在連串過關時為了加總今日和早盤的資料而使用
        $dailySport = [];
        $todaySport = [];

        // 足球, 籃球, 網球, 棒球, 美式足球, 其他......
        $sportid = [1, 2, 3, 8, 19,
            5, 6, 7, 11, 13, 15, 18, 21, 23, 25, 29, 31, 32, 34, 40, 41, 45];

        // Key與getEventInfoByPage的market對應
        $map = [
            1 => 2,    // Key = 1(今日)(含滾球)
            2 => 1     // Key = 2(早盤)
        ];

        // Key = 3(連串過關)
        if ($Key == 3) {
            $imSport = new RepositoryIMSport();
            $openParlay = $imSport->getAllSportCount('CHS', true);
            $openParlaySport = [];

            if (!empty($openParlay['SportCount'])) {
                foreach ($openParlay['SportCount'] as $openParlayValue) {
                    $total = ['total' => $openParlayValue['Count']];
                    $openParlaySport += [$openParlayValue['SportId'] => $total];
                }
            }

            foreach ($sportid as $sportId) {
                $dailyOpenParlay = $this->calcPCCount($sportId, 1, 'openParlay');
                $todayOpenParlay = $this->calcPCCount($sportId, 2, 'openParlay');

                $dailySport[$sportId] = [
                    'count' => $dailyOpenParlay['count'],
                    'total' => 0
                ];
                $todaySport[$sportId] = [
                    'count' => $todayOpenParlay['count'],
                    'total' => 0
                ];
            }
            // 將早餐和今日的數量相加
            foreach ($dailySport as $sportId => $sportCount) {
                foreach ($sportCount['count'] as $betTypeId => $betType) {
                    $dailySport[$sportId]['count'][$betTypeId]['count'] += $todaySport[$sportId]['count'][$betTypeId]['count'];
                }
                foreach ($openParlaySport as $sportid => $total) {
                    if ($sportId == $sportid) {
                        $dailySport[$sportId]['total'] = $total['total'];
                    }
                }

                // 將計算完的陣列按照 sportId 分成 sportMain、sportRest
                if (in_array($sportId, [1, 2, 3, 8, 19])) {
                    $sportMain[] = [
                        'betTypes' => $dailySport[$sportId]['count'],
                        'count' => $dailySport[$sportId]['total'],
                        'sportId' => $sportId
                    ];
                } else {
                    $sportRest[] = [
                        'betTypes' => $dailySport[$sportId]['count'],
                        'count' => $dailySport[$sportId]['total'],
                        'sportId' => $sportId
                    ];
                }
            }
        } else {
            // 當Key = 1或2(今日或早盤)
            foreach ($sportid as $sportId) {
                $notOpenParlay = $this->calcPCCount($sportId, $map[$Key], 'normal');
                if (in_array($sportId, [1, 2, 3, 8, 19])) {
                    $sportMain[] = [
                        'betTypes' => $notOpenParlay['count'],
                        'count' => $notOpenParlay['total'],
                        'sportId' => $sportId
                    ];
                } else {
                    $sportRest[] = [
                        'betTypes' => $notOpenParlay['count'],
                        'count' => $notOpenParlay['total'],
                        'sportId' => $sportId
                    ];
                }
            }
        }

        $sportid = [1, 2, 3, 8, 19,
            5, 6, 7, 11, 13, 15, 18, 21, 23, 25, 29, 31, 32, 34, 40, 41, 45];
        // 將滾球的賽事計數加到 “今日”和"連串過關" 中
        $onRoll = $this->calcOnRollCount($Key);
        // 有滾球中的賽事資料，且不為早盤的話
        if (!empty($onRoll) & $Key != 2) {
            foreach ($onRoll as $sportId => $rollCount) {
                foreach ($rollCount as $betTypeId => $betTypeCount) {
                    if (in_array($sportId, [1, 2, 3, 8, 19])) {
                        // 若球種ID為五個主要球種的話，加計數在 $sportMain 上
                        $index = array_search($sportId, $sportid);
                        if ($betTypeId != 'total') {
                            $sportMain[$index]['betTypes'][$betTypeId]['count'] += $rollCount[$betTypeId];
                        } else {
                            $sportMain[$index]['count'] += $rollCount['total'];
                        }
                    } else {
                        // 若球種ID不為五個主要球種的話，加計數在 $sportRest 上
                        $index = array_search($sportId, $sportid) - 5;
                        if ($betTypeId != 'total') {
                            $sportRest[$index]['betTypes'][$betTypeId]['count'] += $rollCount[$betTypeId];
                        } else {
                            $sportRest[$index]['count'] += $rollCount['total'];
                        }
                    }
                }
            }
        }
        if ($sportMain && $sportRest) {
            // 當回傳資料 sportMain、sportRest都不為空陣列
            $return = [
                'sportMain' => $sportMain,
                'sportRest' => $sportRest,
                'sys_result' => 'Success',
                'sys_code' => 200
            ];
        } else {
            throw new \Exception('API-storeBettypeCount 背景处理异常', 112033005);
        }

        $response = $betCount->storeBettypeCount($return, $Key);

        return $response;
    }

    // 計算賽事計數
    public function calcPCCount($sportid, $market, $type)
    {
        $imSport = new RepositoryIMSport();

        $getByPage = [];              // 儲存非串關的賽事資料
        $getByPageOpenParlay = [];    // 儲存串關的賽事資料
        $betTypeCount = [];           // 將賽事資料按照玩法分類整理
        $marketMSG = [];              // 最後回傳的單一球種計數
        $betTypeCount['merge'] = [];  // 儲存混合玩法(讓球&大小)的賽事
        $betTypeCount['total'] = 0;      // 各球種的總計數
        $betid = [1, 2, 3, 5, 6, 7, 9];  // 主要玩法id

        $page = 1;
        do {
            $response = $imSport->getEventInfoByPage($sportid, $market, 1, false, $page);
            if ($page == 1) {
                // 解決莫名的 Undefined index: PageSize 問題
                if (!isset($response['PageSize'])) {
                    $response['PageSize'] = 0;
                }
                $totalPage = $response['PageSize'];

                // 取得冠軍賽事
                if ($type == 'openParlay') {
                    $outrightAPI = $imSport->getOutrightEvents($sportid, 'CHS', true);
                } else {
                    $outrightAPI = $imSport->getOutrightEvents($sportid, 'CHS', false);
                }
                if (!empty($outrightAPI['Events'])) {
                    $outright = sizeof($outrightAPI['Events']);
                } else {
                    $outright = 0;
                }
            }
            if (isset($response['Sports'][0]['Events'])) {
                $betTypeCount['total'] += count($response['Sports'][0]['Events']);
                foreach ($response['Sports'][0]['Events'] as $eventNum => $eventValue) {
                    if ($type == 'openParlay' && $eventValue['OpenParlay']) {
                        foreach ($eventValue['MarketLines'] as $marketNum => $marketValue) {
                            if (!isset($getByPageOpenParlay[$marketValue['BetTypeId']][$eventValue['EventId']])) {
                                $getByPageOpenParlay[$marketValue['BetTypeId']][$eventValue['EventId']] = 0;
                            }
                            $getByPageOpenParlay[$marketValue['BetTypeId']][$eventValue['EventId']]++;
                        }
                    } else {
                        foreach ($eventValue['MarketLines'] as $marketNum => $marketValue) {
                            if (!isset($getByPage[$marketValue['BetTypeId']][$eventValue['EventId']])) {
                                $getByPage[$marketValue['BetTypeId']][$eventValue['EventId']] = 0;
                            }
                            $getByPage[$marketValue['BetTypeId']][$eventValue['EventId']]++;
                        }
                    }
                }
            }
            $page++;
        } while ($page <= $totalPage);

        if ($type == 'openParlay') {
            $response = $getByPageOpenParlay;
        } else {
            $response = $getByPage;
        }

        foreach ($response as $betTypeId => $eventNum) {
            $betTypeCount[$betTypeId]['count'] = 0;
            $betTypeCount[$betTypeId]['count'] = count($eventNum);
            $betTypeCount[$betTypeId]['betTypeId'] = $betTypeId;
            if ($sportid == 1) {
                if ($betTypeId == 1 || $betTypeId == 2 || $betTypeId == 3) {
                    $betTypeCount['merge'] += $eventNum;
                }
            } else {
                if ($betTypeId == 1 || $betTypeId == 2 || $betTypeId == 4) {
                    $betTypeCount['merge'] += $eventNum;
                }
            }
        }
        // betTypeCount陣列加上冠軍計數(id = 11)
        $betTypeCount[11] = [
            'count' => $outright,
            'betTypeId' => 11
        ];
        // betTypeCount陣列計算混合玩法(讓球&大小)
        $betTypeCount[1] = [
            'count' => count($betTypeCount['merge']),
            'betTypeId' => 1
        ];

        // 球種為足球時的玩法
        if ($sportid == 1) {
            $marketMSG = [
                '1' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 1], // 1代表：讓球&大小&1x2
                '3' => $betTypeCount[3] ?? ['count' => 0, 'betTypeId' => 3], // 3代表：亞博足球的獨贏是1x2 ！！
                '5' => $betTypeCount[5] ?? ['count' => 0, 'betTypeId' => 5], // 5代表：單/雙
                '6' => $betTypeCount[6] ?? ['count' => 0, 'betTypeId' => 6], // 6代表：波膽
                '7' => $betTypeCount[7] ?? ['count' => 0, 'betTypeId' => 7], // 7代表：總入球 = 總進球
                '9' => $betTypeCount[9] ?? ['count' => 0, 'betTypeId' => 9], // 9代表：半場/全場
                '11' => $betTypeCount[11] ?? ['count' => 0, 'betTypeId' => 11], // 11代表：冠軍
            ];
            $total = $betTypeCount['total'] + $betTypeCount[11]['count'];
        } else {
            // 其他球種時的玩法
            $marketMSG = [
                '1' => $betTypeCount[1] ?? [],       // 1代表：讓球&大小&獨贏
                '11' => $betTypeCount[11] ?? [],
            ];
            $total = $betTypeCount['total'] + $betTypeCount[11]['count'];
        }

        $return = [
            'count' => $marketMSG,
            'total' => $total
        ];
        return $return;
    }

    // 計算滾球中的賽事計數
    public function calcOnRollCount($Key)
    {
        $imSport = new RepositoryIMSport();

        // 足球, 籃球, 網球, 棒球, 美式足球, 其他......
        $sportid = [1, 2, 3, 8, 19,
            5, 6, 7, 11, 13, 15, 18, 21, 23, 25, 29, 31, 32, 34, 40, 41, 45];
        // 玩法(bettype)id
        $betid = [1, 2, 3, 5, 6, 7, 9];
        $marketMSG = [];


        if ($Key == 3) {
            $islive = $imSport->getLiveEventInfo($sportid, 1, true, null, $betid);
        } else {
            $islive = $imSport->getLiveEventInfo($sportid, 1, false, null, $betid);
        }

        // 若有滾球有賽事資料的話(Sports不為空陣列)
        if (!empty($islive['Sports'])) {
            foreach ($islive['Sports'] as $sportId => $sportItem) {
                // 直接計算每個球種有幾場賽事
                $betTypeCount[$sportItem['SportId']] = count($sportItem['Events']);
            }

            foreach ($betTypeCount as $sportId => $sportBetType) {
                // 球種為足球時的玩法
                if ($sportId == 1) {
                    $marketMSG[1] = [
                        '1' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 1], // 1代表：讓球&大小&1x2
                        '3' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 3], // 3代表：亞博足球的獨贏是1x2 ！！
                        '5' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 5], // 5代表：單/雙
                        '6' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 6], // 6代表：波膽
                        '7' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 7], // 7代表：總入球 = 總進球
                        '9' => $betTypeCount[1] ?? ['count' => 0, 'betTypeId' => 9], // 9代表：半場/全場
                    ];
                    $marketMSG[1]['total'] = $betTypeCount[1];
                } else {
                    // 其他球種時的玩法
                    $marketMSG[$sportId] = [
                        '1' => $betTypeCount[$sportId] ?? [],
                    ];
                    $marketMSG[$sportId]['total'] = $betTypeCount[$sportId];
                }
            }
        }

        return $marketMSG;
    }

    // 索取所有日期(早餐+今日)的賽事
    public function getEventInfoAllMarket($sportId, $page, $orderBy, $oddsType, $isCombo, $languageCode)
    {
        $imSport = new RepositoryIMSport();
        $byPage = 0;      // $byPage : 取得早餐時的指定頁數

        # 滾球
        $responseForlive = $imSport->getLiveEventInfo([$sportId], $oddsType, $isCombo, $languageCode
            , [1, 2, 3, 4, 5, 6, 7, 9], $orderBy);

        # 今日
        $responseForToday = $imSport->getEventInfoByPage($sportId, 2, $oddsType, $isCombo, $page,
            $languageCode, null, [1, 2, 3, 4, 5, 6, 7, 9], $orderBy);
        $todayPage = $responseForToday['PageSize'];
        // 如果頁數超過總頁數
        if ($page > $todayPage) {
            $responseForToday['Sports'][0]['Events'] = [];
        }

        # 早餐
        $responseForDaily = $imSport->getEventInfoByPage($sportId, 1, $oddsType, $isCombo, $page,
            $languageCode, null, [1, 2, 3, 4, 5, 6, 7, 9], $orderBy);
        $dailyPage = $responseForDaily['PageSize'];
        // 如果頁數超過總頁數
        if ($page > $dailyPage) {
            $responseForDaily['Sports'][0]['Events'] = [];
        }

        // 總頁數($pageSize)
        $pageSize = $todayPage + $dailyPage - 1;
        // 如果今日只有一頁
        if ($todayPage == 1) {
            $byPage = $page - 1;
            $pageSize = $todayPage + $dailyPage;
            if ($dailyPage == 1) {
                // 如果早餐也只有一頁
                $byPage = 1;
                $pageSize = 1;
            }
        } else {
            $byPage = $page - $todayPage + 1;
        }

        // 判斷有無滾球的賽事資料
        if (!empty($responseForlive['Sports'])) {
            $response = $responseForlive;

            if ($page == 1) {
                if (empty($responseForToday['Sports'])) {
                    $responseForToday['Sports'][0]['Events'] = [];
                }
                // 將Events賽事資訊合併(滾球加上今日)
                foreach ($responseForToday['Sports'][0]['Events'] as $mergeId => $mergeValue) {
                    array_push($response['Sports'][0]['Events'], $mergeValue);
                }

                if (empty($responseForDaily['Sports'])) {
                    $responseForDaily['Sports'][0]['Events'] = [];
                }
                if ($dailyPage == 1) {
                    // 如果早餐也只有一頁
                    foreach ($responseForDaily['Sports'][0]['Events'] as $mergeId => $mergeValue) {
                        array_push($response['Sports'][0]['Events'], $mergeValue);
                    }
                }
            } else {
                $response = $responseForToday;

                // 早餐
                $responseForDaily = $imSport->getEventInfoByPage($sportId, 1, $oddsType, $isCombo, $byPage,
                    $languageCode, null, [1, 2, 3, 4, 5, 6, 7, 9], $orderBy);

                if (empty($responseForDaily['Sports'])) {
                    $responseForDaily['Sports'][0]['Events'] = [];
                }
                // 將Events賽事資訊合併(今日加上早餐)
                foreach ($responseForDaily['Sports'][0]['Events'] as $mergeId => $mergeValue) {
                    array_push($response['Sports'][0]['Events'], $mergeValue);
                }
            }

        } else {
            // 如果無賽事資料
            if (empty($responseForToday['Sports'])) {
                $responseForToday['Sports'][0]['Events'] = [];
                $response = $responseForDaily;
                $todayIsNull = true;
            } else {
                $response = $responseForToday;
                $todayIsNull = false;
            }

            # 早餐 (根據新的指定頁數，重新呼叫早餐的賽事資訊)
            $responseForDaily = $imSport->getEventInfoByPage($sportId, 1, $oddsType, $isCombo, $byPage,
                $languageCode, null, [1, 2, 3, 4, 5, 6, 7, 9], $orderBy);
            if (empty($responseForDaily['Sports'])) {
                $responseForDaily['Sports'][0]['Events'] = [];
            }

            if ($todayIsNull) {
                $response = $responseForDaily;
            } else {
                // 將Events賽事資訊合併
                foreach ($responseForDaily['Sports'][0]['Events'] as $mergeId => $mergeValue) {
                    array_push($response['Sports'][0]['Events'], $mergeValue);
                }
            }
        }

        // 判斷頁數超出總頁數
        if ($page > $pageSize) {
            $response['Sports'] = [];
        }
        // 判斷全無賽事資料
        if (isset($response['Sports'][0]['Events']) && empty($response['Sports'][0]['Events'])) {
            $response['Sports'] = [];
        }

        $response['PageSize'] = $pageSize;
        $response['Page'] = (int)$page;

        return $response;
    }
}
