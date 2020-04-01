<?php


namespace App\Repository;

/**
 * Include Model
 */
class IMSport
{
    private $curl;
    private $timeStamp;

    public function __construct()
    {
        $this->curl = \App::make('IMSport'); // 正式環境 *切換時要改timeStamp加密串
        $this->timeStamp = $this->timeStampEncryptString();
//        dd($this->timeStamp);
    }

    public function timeStampEncryptString()
    {
        $string = config('services.IMSport.access-key'); //加密字串
        $hash = md5($string);
        $hex = pack('H*', $hash);
        $key = unpack('C*', $hex);
        $keyString = implode(array_map("chr", $key));

        date_default_timezone_set("GMT");
        $utcTime = str_replace("+0000", "GMT", date("r", time()));

        $method = 'AES-128-ECB';
        return openssl_encrypt($utcTime, $method, $keyString, 0, '');
    }

    // 索取所有體育計數
    public function getAllSportCount($languageCode, $isCombo)
    {
        $param = [
            'LanguageCode' => $languageCode,
            'IsCombo' => $isCombo,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getAllSportCount', json_encode($param)), true);
    }

    // 索取所有競賽計數
    public function getAllCompetitionCount(
        $languageCode,
        $sportId,
        $isCombo,
        $market,
        $includeCloseEvent,
        $eventDate = null,
        $eventGroupTypeIds = null,
        $programmeId = null
    )
    {
        $param = [
            'TimeStamp' => $this->timeStamp,
            'LanguageCode' => $languageCode,
            'SportId' => $sportId,
            'IsCombo' => $isCombo,
            'Market' => $market,
            'IncludeCloseEvent' => $includeCloseEvent
        ];

        if (!is_null($eventDate)) {
            $param['EventDate'] = $eventDate;
        }
        if (!is_null($eventGroupTypeIds)) {
            $param['EventGroupTypeIds'] = $eventGroupTypeIds;
        }
        if (!is_null($programmeId)) {
            $param['ProgrammeId'] = $programmeId;
        }

        return json_decode($this->curl->post('/api/mobile/getAllCompetitionCount', json_encode($param)), true);
    }

    // 索取賽事資料
    public function getEventInfo($sportId, $market, $betTypeIds = null, $eventDate = null,
                                 $languageCode = null, $eventGroupTypeIds = null)
    {
        $param = [
            'SportId' => $sportId,
            'Market' => $market,
            'TimeStamp' => $this->timeStamp
        ];
        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($eventDate)) {
            $param['EventDate'] = $eventDate;
        }
        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }
        if (!is_null($eventGroupTypeIds)) {
            $param['EventGroupTypeIds'] = $eventGroupTypeIds;
        }

        $response = json_decode($this->curl->post('/api/mobile/getEventInfo', json_encode($param)), true);

        // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
        if (!empty($response['Sports'])) {
            foreach ($response['Sports'][0]['Events'] as &$event) {
                $event['IsChat'] = false;
                if ($event['Market'] == 3) {
                    $event['IsChat'] = true;
                }
                foreach ($event['MarketLines'] as &$marketLine) {
                    // 加上 BetTypeNames (用來替換原本的 BetTypeName)
                    $marketLine['BetTypeNames'] = $marketLine['BetTypeName'];
                    if (strchr($marketLine['BetTypeName'], "{")) {
                        $specifiers = $marketLine['WagerSelections'][0]['Specifiers'];
                        if (strchr($specifiers, "from")) {
                            // {from to} 的 BetTypeNames名稱處理
                            $explode = explode("&", $specifiers);
                            $changedStr = [];
                            foreach ($explode as $ex) {
                                if (strchr($ex, "from")) {
                                    $fromA = explode("=", $ex)[1];
                                    $changedStr += ["{from}" => $fromA];
                                }

                                if (strchr($ex, "to")) {
                                    $toB = explode("=", $ex)[1];
                                    $changedStr += ["{to}" => $toB];
                                }

                                if (strchr($ex, "goalnr")) {
                                    $goalnr = explode("=", $ex)[1];
                                    $changedStr += ["{goalnr}" => $goalnr];
                                }

                                if (strchr($ex, "total")) {
                                    $total = explode("=", $ex)[1];
                                    $changedStr += ["{total}" => $total];
                                }
                            }
                            $marketLine['BetTypeNames'] = strtr($marketLine['BetTypeNames'], $changedStr);
                        } else {
                            // 其餘只有一個 {變數} 的 BetTypeNames名稱處理
                            $hcp = explode("=", $marketLine['WagerSelections'][0]['Specifiers']);
                            $name = $hcp[1];
                            $start = strpos($marketLine['BetTypeName'], '{');
                            $length = strpos($marketLine['BetTypeName'], '}') - $start + 1;

                            $marketLine['BetTypeNames'] = substr_replace($marketLine['BetTypeName'], $name, $start, $length);
                        }
                    }
                    foreach ($marketLine['WagerSelections'] as &$wager) {
                        // 加上 SelectionNames (用來替換原本的 SelectionName)
                        $wager['SelectionNames'] = $wager['SelectionName'];
                        if (strchr($wager['Specifiers'], "hcp")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $specifiers = explode(":", $hcp[1]);
                            if (strchr($wager['SelectionName'], "{y-x}")) {
                                $name = $specifiers[1] - $specifiers[0];
                                $wager['SelectionNames'] = str_replace('{y-x}', $name, $wager['SelectionName']);
                            }
                            if (strchr($wager['SelectionName'], "{x-y}")) {
                                $name = $specifiers[0] - $specifiers[1];
                                $wager['SelectionNames'] = str_replace('{x-y}', $name, $wager['SelectionName']);

                            }
                        }

                        if (strchr($wager['Specifiers'], "total")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{total}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "quarternr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{quarternr}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "goalnr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{goalnr}', $name, $wager['SelectionName']);
                        }
                    }
                }
            }
        }
        return $response;
    }

    // 索取DELTA賽事詳情 (沒有加 "IsChat"和"BetTypeNames"、"SelectionNames")
    public function getDeltaEventInfo($sportId, $market, $delta)
    {
        $param = [
            'SportId' => $sportId,
            'Market' => $market,
            'Delta' => $delta,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getDeltaEventInfo', json_encode($param)), true);
    }

    // 以頁數索取賽事資料
    public function getEventInfoByPage($sportId, $market, $oddsType, $isCombo, $page, $languageCode = null,
                                       $eventDate = null, $betTypeIds = null, $orderBy = null, $periodIds = null,
                                       $marketLineLevels = null, $eventGroupTypeId = null, $competitionIds = null,
                                       $token = null, $memberCode = null)
    {
        $param = [
            'SportId' => $sportId,
            'Market' => $market,
            'OddsType' => $oddsType,
            'IsCombo' => $isCombo,
            'Page' => $page,
            'TimeStamp' => $this->timeStamp,
        ];

        if (!is_null($eventDate)) {
            $param['EventDate'] = $eventDate;
        }
        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($periodIds)) {
            $param['PeriodIds'] = $periodIds;
        }
        if (!is_null($marketLineLevels)) {
            $param['MarketlineLevels'] = $marketLineLevels;
        }
        if (!is_null($eventGroupTypeId)) {
            $param['EventGroupTypeId'] = $eventGroupTypeId;
        }
        if (!is_null($orderBy)) {
            $param['OrderBy'] = $orderBy;
        }
        if (!is_null($competitionIds)) {
            $param['CompetitionIds'] = $competitionIds;
        }
        if (!is_null($token)) {
            $param['Token'] = $token;
        }
        if (!is_null($memberCode)) {
            $param['MemberCode'] = $memberCode;
        }
        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }

        $response = json_decode($this->curl->post('/api/mobile/getEventInfoByPage', json_encode($param)), true);

        // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
        if (!empty($response['Sports'])) {
            foreach ($response['Sports'][0]['Events'] as &$event) {
                $event['IsChat'] = false;
                if ($event['Market'] == 3) {
                    $event['IsChat'] = true;
                }
                foreach ($event['MarketLines'] as &$marketLine) {
                    // 加上 BetTypeNames (用來替換原本的 BetTypeName)
                    $marketLine['BetTypeNames'] = $marketLine['BetTypeName'];
                    if (strchr($marketLine['BetTypeName'], "{")) {
                        $specifiers = $marketLine['WagerSelections'][0]['Specifiers'];
                        if (strchr($specifiers, "from")) {
                            // {from to} 的 BetTypeNames名稱處理
                            $explode = explode("&", $specifiers);
                            $changedStr = [];
                            foreach ($explode as $ex) {
                                if (strchr($ex, "from")) {
                                    $fromA = explode("=", $ex)[1];
                                    $changedStr += ["{from}" => $fromA];
                                }

                                if (strchr($ex, "to")) {
                                    $toB = explode("=", $ex)[1];
                                    $changedStr += ["{to}" => $toB];
                                }

                                if (strchr($ex, "goalnr")) {
                                    $goalnr = explode("=", $ex)[1];
                                    $changedStr += ["{goalnr}" => $goalnr];
                                }

                                if (strchr($ex, "total")) {
                                    $total = explode("=", $ex)[1];
                                    $changedStr += ["{total}" => $total];
                                }
                            }
                            $marketLine['BetTypeNames'] = strtr($marketLine['BetTypeNames'], $changedStr);
                        } else {
                            // 其餘只有一個 {變數} 的 BetTypeNames名稱處理
                            $hcp = explode("=", $marketLine['WagerSelections'][0]['Specifiers']);
                            $name = $hcp[1];
                            $start = strpos($marketLine['BetTypeName'], '{');
                            $length = strpos($marketLine['BetTypeName'], '}') - $start + 1;

                            $marketLine['BetTypeNames'] = substr_replace($marketLine['BetTypeName'], $name, $start, $length);
                        }
                    }
                    foreach ($marketLine['WagerSelections'] as &$wager) {
                        // 加上 SelectionNames (用來替換原本的 SelectionName)
                        $wager['SelectionNames'] = $wager['SelectionName'];
                        if (strchr($wager['Specifiers'], "hcp")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $specifiers = explode(":", $hcp[1]);
                            if (strchr($wager['SelectionName'], "{y-x}")) {
                                $name = $specifiers[1] - $specifiers[0];
                                $wager['SelectionNames'] = str_replace('{y-x}', $name, $wager['SelectionName']);
                            }
                            if (strchr($wager['SelectionName'], "{x-y}")) {
                                $name = $specifiers[0] - $specifiers[1];
                                $wager['SelectionNames'] = str_replace('{x-y}', $name, $wager['SelectionName']);

                            }
                        }

                        if (strchr($wager['Specifiers'], "total")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{total}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "quarternr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{quarternr}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "goalnr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{goalnr}', $name, $wager['SelectionName']);
                        }
                    }
                }
            }
        }
        return $response;
    }

    // 索取滾球賽事資料
    public function getLiveEventInfo($sportIds, $oddsType, $isCombo, $languageCode = null, $betTypeIds = null,
                                     $orderBy = null, $periodIds = null, $marketLineLevels = null,
                                     $eventGroupTypeId = null, $competitionIds = null, $token = null, $memberCode = null)
    {
        $param = [
            'SportIds' => $sportIds,
            'OddsType' => $oddsType,
            'IsCombo' => $isCombo,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($periodIds)) {
            $param['PeriodIds'] = $periodIds;
        }
        if (!is_null($marketLineLevels)) {
            $param['MarketlineLevels'] = $marketLineLevels;
        }
        if (!is_null($eventGroupTypeId)) {
            $param['EventGroupTypeId'] = $eventGroupTypeId;
        }
        if (!is_null($orderBy)) {
            $param['OrderBy'] = $orderBy;
        }
        if (!is_null($competitionIds)) {
            $param['CompetitionIds'] = $competitionIds;
        }
        if (!is_null($token)) {
            $param['Token'] = $token;
        }
        if (!is_null($memberCode)) {
            $param['MemberCode'] = $memberCode;
        }
        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }

        $response = json_decode($this->curl->post('/api/mobile/getLiveEventInfo', json_encode($param)), true);

        // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
        if (!empty($response['Sports'])) {
            foreach ($response['Sports'] as &$sport) {
                // 如果參數(SportIds)帶多球種，則foreach每一個球種
                foreach ($sport['Events'] as &$event) {
                    $event['IsChat'] = false;
                    if ($event['Market'] == 3) {
                        $event['IsChat'] = true;
                    }
                    foreach ($event['MarketLines'] as &$marketLine) {
                        // 加上 BetTypeNames (用來替換原本的 BetTypeName)
                        $marketLine['BetTypeNames'] = $marketLine['BetTypeName'];
                        if (strchr($marketLine['BetTypeName'], "{")) {
                            $specifiers = $marketLine['WagerSelections'][0]['Specifiers'];
                            if (strchr($specifiers, "from")) {
                                // {from to} 的 BetTypeNames名稱處理
                                $explode = explode("&", $specifiers);
                                $changedStr = [];
                                foreach ($explode as $ex) {
                                    if (strchr($ex, "from")) {
                                        $fromA = explode("=", $ex)[1];
                                        $changedStr += ["{from}" => $fromA];
                                    }

                                    if (strchr($ex, "to")) {
                                        $toB = explode("=", $ex)[1];
                                        $changedStr += ["{to}" => $toB];
                                    }

                                    if (strchr($ex, "goalnr")) {
                                        $goalnr = explode("=", $ex)[1];
                                        $changedStr += ["{goalnr}" => $goalnr];
                                    }

                                    if (strchr($ex, "total")) {
                                        $total = explode("=", $ex)[1];
                                        $changedStr += ["{total}" => $total];
                                    }
                                }
                                $marketLine['BetTypeNames'] = strtr($marketLine['BetTypeNames'], $changedStr);
                            } else {
                                // 其餘只有一個 {變數} 的 BetTypeNames名稱處理
                                $hcp = explode("=", $marketLine['WagerSelections'][0]['Specifiers']);
                                $name = $hcp[1];
                                $start = strpos($marketLine['BetTypeName'], '{');
                                $length = strpos($marketLine['BetTypeName'], '}') - $start + 1;

                                $marketLine['BetTypeNames'] = substr_replace($marketLine['BetTypeName'], $name, $start, $length);
                            }
                        }
                        foreach ($marketLine['WagerSelections'] as &$wager) {
                            // 加上 SelectionNames (用來替換原本的 SelectionName)
                            $wager['SelectionNames'] = $wager['SelectionName'];
                            if (strchr($wager['Specifiers'], "hcp")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $specifiers = explode(":", $hcp[1]);
                                if (strchr($wager['SelectionName'], "{y-x}")) {
                                    $name = $specifiers[1] - $specifiers[0];
                                    $wager['SelectionNames'] = str_replace('{y-x}', $name, $wager['SelectionName']);
                                }
                                if (strchr($wager['SelectionName'], "{x-y}")) {
                                    $name = $specifiers[0] - $specifiers[1];
                                    $wager['SelectionNames'] = str_replace('{x-y}', $name, $wager['SelectionName']);

                                }
                            }

                            if (strchr($wager['Specifiers'], "total")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{total}', $name, $wager['SelectionName']);
                            }

                            if (strchr($wager['Specifiers'], "quarternr")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{quarternr}', $name, $wager['SelectionName']);
                            }

                            if (strchr($wager['Specifiers'], "goalnr")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{goalnr}', $name, $wager['SelectionName']);
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }

    // 索取賽事選項資料
    public function getSelectedEventInfo(
        $sportId,
        $eventIds,
        $oddsType,
        $isCombo,
        $includeGroupEvents,
        $languageCode = null,
        $betTypeIds = null,
        $periodIds = null,
        $token = null,
        $memberCode = null
    )
    {
        $param = [
            'SportId' => $sportId,
            'EventIds' => $eventIds,
            'OddsType' => $oddsType,
            'IsCombo' => $isCombo,
            'IncludeGroupEvents' => $includeGroupEvents,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($periodIds)) {
            $param['PeriodIds'] = $periodIds;
        }
        if (!is_null($token)) {
            $param['Token'] = $token;
        }
        if (!is_null($memberCode)) {
            $param['MemberCode'] = $memberCode;
        }
        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }

        $response = json_decode($this->curl->post('/api/mobile/getSelectedEventInfo', json_encode($param)), true);

        // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
        if (!empty($response['Sports'])) {
            foreach ($response['Sports'][0]['Events'] as &$event) {
                $event['IsChat'] = false;
                if ($event['Market'] == 3) {
                    $event['IsChat'] = true;
                }
                foreach ($event['MarketLines'] as &$marketLine) {
                    // 加上 BetTypeNames (用來替換原本的 BetTypeName)
                    $marketLine['BetTypeNames'] = $marketLine['BetTypeName'];
                    if (strchr($marketLine['BetTypeName'], "{")) {
                        $specifiers = $marketLine['WagerSelections'][0]['Specifiers'];
                        if (strchr($specifiers, "from")) {
                            // {from to} 的 BetTypeNames名稱處理
                            $explode = explode("&", $specifiers);
                            $changedStr = [];
                            foreach ($explode as $ex) {
                                if (strchr($ex, "from")) {
                                    $fromA = explode("=", $ex)[1];
                                    $changedStr += ["{from}" => $fromA];
                                }

                                if (strchr($ex, "to")) {
                                    $toB = explode("=", $ex)[1];
                                    $changedStr += ["{to}" => $toB];
                                }

                                if (strchr($ex, "goalnr")) {
                                    $goalnr = explode("=", $ex)[1];
                                    $changedStr += ["{goalnr}" => $goalnr];
                                }

                                if (strchr($ex, "total")) {
                                    $total = explode("=", $ex)[1];
                                    $changedStr += ["{total}" => $total];
                                }
                            }
                            $marketLine['BetTypeNames'] = strtr($marketLine['BetTypeNames'], $changedStr);
                        } else {
                            // 其餘只有一個 {變數} 的 BetTypeNames名稱處理
                            $hcp = explode("=", $marketLine['WagerSelections'][0]['Specifiers']);
                            $name = $hcp[1];
                            $start = strpos($marketLine['BetTypeName'], '{');
                            $length = strpos($marketLine['BetTypeName'], '}') - $start + 1;

                            $marketLine['BetTypeNames'] = substr_replace($marketLine['BetTypeName'], $name, $start, $length);
                        }
                    }
                    foreach ($marketLine['WagerSelections'] as &$wager) {
                        // 加上 SelectionNames (用來替換原本的 SelectionName)
                        $wager['SelectionNames'] = $wager['SelectionName'];
                        if (strchr($wager['Specifiers'], "hcp")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $specifiers = explode(":", $hcp[1]);
                            if (strchr($wager['SelectionName'], "{y-x}")) {
                                $name = $specifiers[1] - $specifiers[0];
                                $wager['SelectionNames'] = str_replace('{y-x}', $name, $wager['SelectionName']);
                            }
                            if (strchr($wager['SelectionName'], "{x-y}")) {
                                $name = $specifiers[0] - $specifiers[1];
                                $wager['SelectionNames'] = str_replace('{x-y}', $name, $wager['SelectionName']);

                            }
                        }

                        if (strchr($wager['Specifiers'], "total")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{total}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "quarternr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{quarternr}', $name, $wager['SelectionName']);
                        }

                        if (strchr($wager['Specifiers'], "goalnr")) {
                            $hcp = explode("=", $wager['Specifiers']);
                            $name = $hcp[1];
                            $wager['SelectionNames'] = str_replace('{goalnr}', $name, $wager['SelectionName']);
                        }
                    }
                }
            }
        }
        return $response;
    }

    // 索取熱門賽事
    public function getPopularEvent($oddsType, $languageCode, $isCombo, $token = null, $memberCode = null,
                                    $sportIds = null, $betTypeIds = null, $periodIds = null, $marketLineLevels = null)
    {
        $param = [
            'OddsType' => $oddsType,
            'LanguageCode' => $languageCode,
            'IsCombo' => $isCombo,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($token)) {
            $param['Token'] = $token;
        }
        if (!is_null($memberCode)) {
            $param['MemberCode'] = $memberCode;
        }
        if (!is_null($sportIds)) {
            $param['SportIds'] = $sportIds;
        }
        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($periodIds)) {
            $param['PeriodIds'] = $periodIds;
        }
        if (!is_null($marketLineLevels)) {
            $param['MarketlineLevels'] = $marketLineLevels;
        }

        $response = json_decode($this->curl->post('/api/mobile/getPopularEvent', json_encode($param)), true);

        // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
        if (!empty($response['Sports'])) {
            foreach ($response['Sports'] as &$sport) {
                // 如果參數(SportIds)帶多球種，則foreach每一個球種
                foreach ($sport['Events'] as &$event) {
                    $event['IsChat'] = false;
                    if ($event['Market'] == 3) {
                        $event['IsChat'] = true;
                    }
                    foreach ($event['MarketLines'] as &$marketLine) {
                        // 加上 BetTypeNames (用來替換原本的 BetTypeName)
                        $marketLine['BetTypeNames'] = $marketLine['BetTypeName'];
                        if (strchr($marketLine['BetTypeName'], "{")) {
                            $specifiers = $marketLine['WagerSelections'][0]['Specifiers'];
                            if (strchr($specifiers, "from")) {
                                // {from to} 的 BetTypeNames名稱處理
                                $explode = explode("&", $specifiers);
                                $changedStr = [];
                                foreach ($explode as $ex) {
                                    if (strchr($ex, "from")) {
                                        $fromA = explode("=", $ex)[1];
                                        $changedStr += ["{from}" => $fromA];
                                    }

                                    if (strchr($ex, "to")) {
                                        $toB = explode("=", $ex)[1];
                                        $changedStr += ["{to}" => $toB];
                                    }

                                    if (strchr($ex, "goalnr")) {
                                        $goalnr = explode("=", $ex)[1];
                                        $changedStr += ["{goalnr}" => $goalnr];
                                    }

                                    if (strchr($ex, "total")) {
                                        $total = explode("=", $ex)[1];
                                        $changedStr += ["{total}" => $total];
                                    }
                                }
                                $marketLine['BetTypeNames'] = strtr($marketLine['BetTypeNames'], $changedStr);
                            } else {
                                // 其餘只有一個 {變數} 的 BetTypeNames名稱處理
                                $hcp = explode("=", $marketLine['WagerSelections'][0]['Specifiers']);
                                $name = $hcp[1];
                                $start = strpos($marketLine['BetTypeName'], '{');
                                $length = strpos($marketLine['BetTypeName'], '}') - $start + 1;

                                $marketLine['BetTypeNames'] = substr_replace($marketLine['BetTypeName'], $name, $start, $length);
                            }
                        }
                        foreach ($marketLine['WagerSelections'] as &$wager) {
                            // 加上 SelectionNames (用來替換原本的 SelectionName)
                            $wager['SelectionNames'] = $wager['SelectionName'];
                            if (strchr($wager['Specifiers'], "hcp")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $specifiers = explode(":", $hcp[1]);
                                if (strchr($wager['SelectionName'], "{y-x}")) {
                                    $name = $specifiers[1] - $specifiers[0];
                                    $wager['SelectionNames'] = str_replace('{y-x}', $name, $wager['SelectionName']);
                                }
                                if (strchr($wager['SelectionName'], "{x-y}")) {
                                    $name = $specifiers[0] - $specifiers[1];
                                    $wager['SelectionNames'] = str_replace('{x-y}', $name, $wager['SelectionName']);

                                }
                            }

                            if (strchr($wager['Specifiers'], "total")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{total}', $name, $wager['SelectionName']);
                            }

                            if (strchr($wager['Specifiers'], "quarternr")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{quarternr}', $name, $wager['SelectionName']);
                            }

                            if (strchr($wager['Specifiers'], "goalnr")) {
                                $hcp = explode("=", $wager['Specifiers']);
                                $name = $hcp[1];
                                $wager['SelectionNames'] = str_replace('{goalnr}', $name, $wager['SelectionName']);
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }

    // 索取優勝冠軍賽事
    // 沒有加 SelectionName => SelectionNames的替換
    public function getOutrightEvents($sportId, $languageCode, $isCombo, $programmeId = null)
    {
        $param = [
            'TimeStamp' => $this->timeStamp,
            'SportId' => $sportId,
            'LanguageCode' => $languageCode,
            'IsCombo' => $isCombo
        ];

        if (!is_null($programmeId)) {
            $param['ProgrammeId'] = $programmeId;
        }

        return json_decode($this->curl->post('/api/mobile/getOutrightEvents', json_encode($param)), true);
    }

    // 索取DELTA優勝冠軍賽事
    public function getDeltaOutrightEventInfo(
        $sportId,
        $isCombo,
        $delta,
        $languageCode = null,
        $programmeId = null
    )
    {
        $param = [
            'SportId' => $sportId,
            'IsCombo' => $isCombo,
            'Delta' => $delta,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }
        if (!is_null($programmeId)) {
            $param['ProgrammeId'] = $programmeId;
        }

        return json_decode($this->curl->post('/api/mobile/getDeltaOutrightEventInfo', json_encode($param)), true);
    }

    // 搜索賽事
    public function search($sportId, $filter, $languageCode, $isCombo)
    {
        $param = [
            'SportId' => $sportId,
            'Filter' => $filter,
            'LanguageCode' => $languageCode,
            'IsCombo' => $isCombo,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/search', json_encode($param)), true);
    }

    // 索取定位
    public function getLocalizations($localizationType)
    {
        $param = [
            'LocalizationType' => $localizationType,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getLocalizations', json_encode($param)), true);
    }

    // 索取 DELTA 定位
    public function getDeltaLocalizations($localizationType, $delta)
    {
        $param = [
            'LocalizationType' => $localizationType,
            'Delta' => $delta,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getDeltaLocalizations', json_encode($param)), true);
    }

    // 索取完整赛果
    public function getCompletedResults(
        $sportId,
        $eventTypeId,
        $startDate,
        $endDate,
        $languageCode,
        $competitionId = null
    )
    {
        $param = [
            'SportId' => $sportId,
            'EventTypeId' => $eventTypeId,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'LanguageCode' => $languageCode,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($competitionId)) {
            $param['CompetitionId'] = $competitionId;
        }

        return json_decode($this->curl->post('/api/mobile/getCompletedResults', json_encode($param)), true);
    }

    // 延遲操作會話
    public function extendSession($token)
    {
        $param = [
            'Token' => $token,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/extendSession', json_encode($param)), true);
    }

    // 會員登出
    public function logOut($token, $memberCode)
    {
        $param = [
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/logOut', json_encode($param)), true);
    }

    // 索取投注信息
    public function getBetInfo(
        $wagerType,
        $wagerSelectionInfos,
        $token,
        $memberCode,
        $returnNearestHandicap = null
    )
    {
        $param = [
            'TimeStamp' => $this->timeStamp,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'WagerType' => $wagerType,
            'WagerSelectionInfos' => $wagerSelectionInfos
        ];

        if (!is_null($returnNearestHandicap)) {
            $param['ReturnNearestHandicap'] = $returnNearestHandicap;
        }

        return json_decode($this->curl->post('/api/mobile/getBetInfo', json_encode($param)), true);
    }

    // 投注
    public function placeBet(
        $wagerType,
        $wagerSelectionInfos,
        $comboSelectionInfos,
        $token,
        $memberCode,
        $languageCode,
        $customerMacAddress = null,
        $appDeviceName = null,
        $appModel = null,
        $appOsVersion = null,
        $appPlatform = null,
        $appVersion = null,
        $isComboAcceptAnyOdds = null
    )
    {
        $param = [
            'WagerType' => $wagerType,
            'CustomerIP' => $_SERVER['REMOTE_ADDR'],
            'ServerIP' => $_SERVER['SERVER_ADDR'],
            'UserAgent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'WagerSelectionInfos' => $wagerSelectionInfos,
            'ComboSelections' => $comboSelectionInfos,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp,
            'LanguageCode' => $languageCode
        ];

        if (!is_null($customerMacAddress)) {
            $param['CustomerMACAddress'] = $customerMacAddress;
        }
        if (!is_null($appDeviceName)) {
            $param['AppDeviceName'] = $appDeviceName;
        }
        if (!is_null($appModel)) {
            $param['AppModel'] = $appModel;
        }
        if (!is_null($appOsVersion)) {
            $param['AppOSVersion'] = $appOsVersion;
        }
        if (!is_null($appPlatform)) {
            $param['AppPlatform'] = $appPlatform;
        }
        if (!is_null($appVersion)) {
            $param['AppVersion'] = $appVersion;
        }
        if (!is_null($isComboAcceptAnyOdds)) {
            $param['IsComboAcceptAnyOdds'] = $isComboAcceptAnyOdds;
        }

        return json_decode($this->curl->post('/api/mobile/placeBet', json_encode($param)), true);
    }

    // 索取投注明细
    public function getBetList(
        $betConfirmationStatus,
        $languageCode,
        $token,
        $memberCode,
        $startDate = null,
        $endDate = null
    )
    {
        $param = [
            'BetConfirmationStatus' => $betConfirmationStatus,
            'LanguageCode' => $languageCode,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($startDate)) {
            $param['StartDate'] = $startDate;
        }
        if (!is_null($endDate)) {
            $param['EndDate'] = $endDate;
        }

        return json_decode($this->curl->post('/api/mobile/getBetList', json_encode($param)), true);
    }

    // 索取投注帳目
    public function getStatement(
        $token,
        $memberCode,
        $startDate,
        $endDate,
        $languageCode,
        $dateType = null,
        $startTime = null,
        $endTime = null
    )
    {
        $param = [
            'Token' => $token,
            'MemberCode' => $memberCode,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'LanguageCode' => $languageCode,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($dateType)) {
            $param['DateType'] = $dateType;
        }
        if (!is_null($startTime)) {
            $param['StartTime'] = $startTime;
        }
        if (!is_null($endTime)) {
            $param['EndTime'] = $endTime;
        }

        return json_decode($this->curl->post('/api/mobile/getStatement', json_encode($param)), true);
    }

    // 取得會員餘額
    public function getBalance($token, $memberCode)
    {
        $param = [
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getBalance', json_encode($param)), true);
    }

    // 索取待處理賭注狀態
    public function getPendingWagerStatus($token, $wagerIds, $memberCode, $languageCode)
    {
        $param = [
            'Token' => $token,
            'WagerIds' => $wagerIds,
            'MemberCode' => $memberCode,
            'LanguageCode' => $languageCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getPendingWagerStatus', json_encode($param)), true);
    }

    // 索取通告
    public function getAnnouncement()
    {
        $param = [
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getAnnouncement', json_encode($param)), true);
    }

    // 索取用戶自定義
    public function getUserPreference($token, $memberCode)
    {
        $param = [
            'TimeStamp' => $this->timeStamp,
            'Token' => $token,
            'MemberCode' => $memberCode
        ];

        return json_decode($this->curl->post('/api/mobile/getUserPreferences', json_encode($param)), true);
    }

    // 更新用户自定義
    public function updateUserPreference(
        $token,
        $memberCode,
        $selectedOddsType,
        $selectedTimeZone,
        $fastBetSingle,
        $fastBetCombo,
        $selectedSport = null,
        $selectedEventSorting = null,
        $sportId = null,
        $selectedViewType = null
    )
    {
        $param = [
            'Token' => $token,
            'MemberCode' => $memberCode,
            'SelectedOddsType' => $selectedOddsType,
            'SelectedTimeZone' => $selectedTimeZone,
            'FastBetSingle' => $fastBetSingle,
            'FastBetCombo' => $fastBetCombo,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($selectedSport)) {
            $param['SelectedSport'] = $selectedSport;
        }
        if (!is_null($selectedEventSorting)) {
            $param['SelectedEventSorting'] = $selectedEventSorting;
        }
        if (!is_null($sportId)) {
            $param['SelectedViewTypeBySport']['SportId'] = $sportId;
        }
        if (!is_null($selectedViewType)) {
            $param['SelectedViewTypeBySport']['SelectedViewType'] = $selectedViewType;
        }

        return json_decode($this->curl->post('/api/mobile/updateUserPreferences', json_encode($param)), true);
    }

    // 索取收藏賽事
    public function getFavouriteEvent(
        $oddsType,
        $memberCode,
        $token,
        $sportIds = null,
        $betTypeIds = null,
        $periodIds = null,
        $marketlineLevels = null,
        $orderBy = null,
        $languageCode = null
    )
    {
        $param = [
            'OddsType' => $oddsType,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($sportIds)) {
            $param['SportIds'] = $sportIds;
        }
        if (!is_null($betTypeIds)) {
            $param['BetTypeIds'] = $betTypeIds;
        }
        if (!is_null($periodIds)) {
            $param['PeriodIds'] = $periodIds;
        }
        if (!is_null($marketlineLevels)) {
            $param['MarketlineLevels'] = $marketlineLevels;
        }
        if (!is_null($orderBy)) {
            $param['OrderBy'] = $orderBy;
        }
        if (!is_null($languageCode)) {
            $param['LanguageCode'] = $languageCode;
        }

        $response = json_decode($this->curl->post('/api/mobile/getFavouriteEvent', json_encode($param)), true);

        if (!empty($response['Sports'])) {
            // 如果有賽事資料的話，每場賽事加上 IsChat (聊天室是否能開啟)
            foreach ($response['Sports'] as &$sport) {
                // 如果參數(SportIds)帶多球種，則foreach每一個球種
                foreach ($sport['Events'] as &$event) {
                    $event['IsChat'] = false;
                    if ($event['Market'] == 3) {
                        $event['IsChat'] = true;
                    }
                }
            }
        }
        return $response;
    }

    // 新增收藏賽事
    public function addFavouriteEvent($sportId, $eventIds, $token, $memberCode, $languageCode)
    {
        $param = [
            'SportId' => $sportId,
            'EventIds' => $eventIds,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'LanguageCode' => $languageCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/addFavouriteEvent', json_encode($param)), true);
    }

    // 移除收藏賽事
    public function removeFavouriteEvent($sportId, $eventIds, $token, $memberCode, $languageCode)
    {
        $param = [
            'SportId' => $sportId,
            'EventIds' => $eventIds,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'LanguageCode' => $languageCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/removeFavouriteEvent', json_encode($param)), true);
    }

    // 索取下注紀錄
    public function getBetTrade($languageCode, $token, $memberCode, $eventId = null)
    {
        $param = [
            'LanguageCode' => $languageCode,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        if (!is_null($eventId)) {
            $param['EventId'] = $eventId;
        }

        return json_decode($this->curl->post('/api/mobile/getBetTrade', json_encode($param)), true);
    }

    // 提交回購
    public function submitBuyBack($wagerId, $buyBackPricing, $pricingId, $token, $memberCode)
    {
        $param = [
            'WagerId' => $wagerId,
            'BuyBackPricing' => $buyBackPricing,
            'PricingId' => $pricingId,
            'Token' => $token,
            'MemberCode' => $memberCode,
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/submitBuyBack', json_encode($param)), true);
    }

    // 索取服務器時間
    public function getServerTime()
    {
        $param = [
            'TimeStamp' => $this->timeStamp
        ];

        return json_decode($this->curl->post('/api/mobile/getServerTime', json_encode($param)), true);
    }
}
