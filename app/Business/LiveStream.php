<?php


namespace App\Business;

use App\Repository\IMSport;
use App\Repository\LiveStream as RepositoryLiveStream;
use DateTime;

class LiveStream
{
    protected $liveStreamRepo;

    public function __construct()
    {
        $this->liveStreamRepo = new RepositoryLiveStream();
    }

    // 取得LiveStream訊源賽事
    private function getStreamData()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://infoapi.zanstartv.com/api/v1/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "project=bbin&secret=8ua3itn9r6a3gy96m23c",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        if (isset($response['code']) && $response['code'] == '0') {
            $token = $response['data']['token'];
            $tokenExpired = $response['data']['tokenExpired'];
        } else {
            die('產生Token失敗!!');
        }

        $curl = curl_init();

        date_default_timezone_set('Asia/Taipei');

        $start_date = date('Y-m-d', time());
        $end_date = date('Y-m-d', strtotime('+1 day'));

        $param = [
            'token' => $token,
            'startDate' => $start_date,
            'endDate' => $end_date,
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://infoapi.zanstartv.com/api/v1/epgs",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($param),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        return $response;
    }

    // 快取直播賽事資訊
    public function cacheLiveList()
    {
        $response = $this->getStreamData();
        if ($response['message'] != 'ok') {
            throw new \Exception("直播訊源異常: {$response['message']}", 112030000); //TODO new exception
        }

        $timezoneIM = new \DateTimeZone('GMT-4');
        $timezoneTPA = new \DateTimeZone('GMT+8');

        $data = [];
        $sourceList = ['stream', 'streamAmAli', 'streamNa'];
        foreach ($response['data'] as $event) {
            $flvList = $m3u8List = [];
            foreach ($sourceList as $sourceName) {
                foreach ($event[$sourceName] ?? [] as $sourceType => $source) {
                    if (!empty($source['flv'])) {
                        $flvList[] = [
                            'Source' => "{$sourceName}_{$sourceType}",
                            'Url' => $source['flv'],
                            'Status' => $source['status']
                        ];
                    }
                    if (!empty($source['m3u8'])) {
                        $m3u8List[] = [
                            'Source' => "{$sourceName}_{$sourceType}",
                            'Url' => $source['m3u8'],
                            'Status' => $source['status']
                        ];
                    }
                }
            }
//            // 針對epg額外處理 TODO 暫時不需此訊源
//            if (!empty($event['epg'])) {
//                foreach ($event['epg'] as $source) {
//                    $urlList[] = [
//                        'Source' => 'epg',
//                        'Url' => $source['urls']['flv']
//                    ];
//                }
//            }
            // 時區轉換 (GMT+8 to GMT-4)
            $date = new DateTime($event['gameTime'], $timezoneTPA);
            $date = $date->setTimezone($timezoneIM)->format('c');

            $data[] = [
                'sport_id' => $event['sportId'],
                'live_stream_id' => $event['matchId'],
                'event_date' => $date,
                'league_chs_name' => $event['lfullname'] ?? $event['lname'],
                'league_eng_name' => $event['lfullnameEN'] ?? $event['lnameEN'],
                'home_chs_name' => $event['hfullname'] ?? $event['hname'],
                'home_eng_name' => $event['hfullnameEN'] ?? $event['hnameEN'],
                'away_chs_name' => $event['afullname'] ?? $event['aname'],
                'away_eng_name' => $event['afullnameEN'] ?? $event['anameEN'],
                'live_stream_url' => '[]',
                'live_stream_flv' => json_encode($flvList),
                'live_stream_m3u8' => json_encode($m3u8List)
            ];
        }
        $this->liveStreamRepo->cacheLiveList($data);
    }

    public function cacheEventList()
    {
        $env = $_ENV['APP_ENV'];
        $imSport = new IMSport();

        $langList = ['chs', 'eng'];
        // 目前只抓足&籃 所有盤口
        for ($sportId = 1; $sportId <= 2; $sportId++) {
            for ($market = 1; $market <= 3; $market++) {
                $eventInfo = [];
                foreach ($langList as $langIndex => $lang) {
                    $data = $imSport->getEventInfo($sportId, $market, [-1], null, $lang, [1]); # 只取母賽資訊
                    if ($data['StatusCode'] != 100) {
                        throw new \Exception("端口資料異常: {$data['StatusCode']}", 112030000); //TODO new exception
                    }

                    foreach ($data['Sports'][0]['Events'] ?? [] as $event) {
                        if ($langIndex == 0) {
                            $date = new DateTime($event['EventDate']);
                            $eventInfo[$event['EventId']] = [
                                'sport_id' => $sportId,
                                'market' => $market,
                                'event_id' => $event['EventGroupId'] != 0 ? $event['EventGroupId'] : $event['EventId'], # 若無母賽ID, 則放賽事ID
                                'event_date' => $date->format('c')
                            ];
                        }
                        $eventInfo[$event['EventId']] += [
                            ### 屏蔽IM測試站的詭異櫻桃🍒 & 花🌸, 避免DB Error ###
                            "league_{$lang}_name" => $env == 'qatest' ? str_replace(['🍒', '🌸'], '', $event['Competition']['CompetitionName']) : $event['Competition']['CompetitionName'],
                            "home_{$lang}_name" => $env == 'qatest' ? str_replace(['🍒', '🌸'], '', $event['HomeTeam']) : $event['HomeTeam'],
                            "away_{$lang}_name" => $env == 'qatest' ? str_replace(['🍒', '🌸'], '', $event['AwayTeam']) : $event['AwayTeam']
                        ];
                    }
                }
                if (!empty($eventInfo)) {
                    $this->liveStreamRepo->cacheEventList(array_values($eventInfo)); //TODO 要分球種？ Market?
                }
            }
        }
    }

    public function getLiveList($startDate, $endDate)
    {
        $data = $this->liveStreamRepo->getLiveList($startDate, $endDate);
        $eventList = $eventData = [];
        $now = date('c', strtotime('-4 hour'));
        foreach ($data as $event) {
            // 判斷賽事是否已開始
            $period = $event['event_date'] < $now ? 'before' : 'after';
            $eventData['sport'][$event['sport_id']][$period][] = [
                'liveEventId' => $event['live_stream_id'],
                'imEventId' => $event['im_event_id'],
                'isCorrespond' => (bool)$event['is_correspond'],
                'eventDate' => $event['event_date'],
                'league' => [
                    'cn' => $event['league_chs_name'],
                    'en' => $event['league_eng_name']
                ],
                'home' => [
                    'cn' => $event['home_chs_name'],
                    'en' => $event['home_eng_name']
                ],
                'away' => [
                    'cn' => $event['away_chs_name'],
                    'en' => $event['away_eng_name']
                ],
                'liveStream' => json_decode($event['live_stream_flv'], true),
                'liveStreamMobile' => json_decode($event['live_stream_m3u8'], true),
                'created' => $event['created_at'],
                'updated' => $event['updated_at']
            ];
        }
        // 依照 未開賽->已開賽 的順序排序
        foreach ($eventData['sport'] ?? [] as $sportId => $sport) {
            $eventList['sport'][$sportId] = array_merge($sport['after'] ?? [], $sport['before'] ?? []);
        }
        return $eventList;
    }

    public function getEventList($startDate, $endDate)
    {
        $data = $this->liveStreamRepo->getEventList($startDate, $endDate);

        $eventList = $eventData = [];
        $now = date('c', strtotime('-4 hour'));
        foreach ($data as $event) {
            // 判斷賽事是否已開始
            $period = $event['event_date'] < $now ? 'before' : 'after';
            $eventData['sport'][$event['sport_id']][$period][] = [
                'imEventId' => $event['event_id'],
                'liveEventId' => $event['live_stream_id'],
                'isCorrespond' => (bool)$event['is_correspond'],
                'eventDate' => $event['event_date'],
                'league' => [
                    'cn' => $event['league_chs_name'],
                    'en' => $event['league_eng_name']
                ],
                'home' => [
                    'cn' => $event['home_chs_name'],
                    'en' => $event['home_eng_name']
                ],
                'away' => [
                    'cn' => $event['away_chs_name'],
                    'en' => $event['away_eng_name']
                ],
                'created' => $event['created_at'],
                'updated' => $event['updated_at']
            ];
        }
        // 依照 未開賽->已開賽 的順序排序
        foreach ($eventData['sport'] ?? [] as $sportId => $sport) {
            $eventList['sport'][$sportId] = array_merge($sport['after'] ?? [], $sport['before'] ?? []);
        }
        return $eventList;
    }

    public function correspondLiveStream($imEventId, $liveEventId)
    {
        $this->liveStreamRepo->correspondLiveStream($imEventId, $liveEventId);
        return [
            'imEventId' => $imEventId,
            'liveEventId' => $liveEventId
        ];
    }

    public function cancelCorrespondLiveStream($eventId, $type)
    {
        $typeList = [1 => 'im_event_id', 2 => 'live_stream_id'];
        list($imEventId, $liveEventId) = $this->liveStreamRepo->cancelCorrespondLiveStream($eventId, $typeList[$type]);
        return [
            'imEventId' => $imEventId,
            'liveEventId' => $liveEventId
        ];
    }

    public function getEventInfoByPage($sportId, $market, $oddsType, $page, $languageCode, $eventDate,
                                       $betTypeIds, $orderBy, $periodIds, $competitionIds, $colorCode)
    {
        // 取得已對應賽事直播資訊 (eventId => liveInfo)
        $liveStreamInfo = $this->liveStreamRepo->getLiveStreamInfoBySportIds([$sportId]);

        $imSport = new IMSport();
        $data = $imSport->getEventInfoByPage(
            $sportId,
            $market,
            $oddsType,
            false,
            $page,
            $languageCode,
            $eventDate,
            $betTypeIds,
            $orderBy,
            $periodIds,
            null,
            null,
            $competitionIds);

        if (!empty($data['Sports'])) {
            foreach ($data['Sports'][0]['Events'] as &$event) {
                // 是否需要色碼 (聯盟名稱)
                if ($colorCode) { # 16進制, 數字越大顏色越淺
                    // 名稱轉16進制
                    $code = dechex(crc32($event['Competition']['CompetitionName']));
                    // 取6碼作為色碼 (hex color)
                    $code = substr($code, 0, 6);
                    // 取出RGB的hex
                    $rgbList = str_split($code, 2);
                    // 當顏色太深, 進行調色
                    foreach ($rgbList as &$rgb) {
                        if ($rgb < $colorCode) {
                            $rgb = dechex(hexdec($rgb) + hexdec($colorCode));
                        }
                    }
                    $code = join('', $rgbList);
                    $event['Competition']['CompetitionColor'] = $code;
                }

                // 回傳格式
                $event['LiveStreaming'] = $event['LiveStreamingMobile'] = false;
                $event['LiveStreamingUrl'] = $event['LiveStreamingMobileUrl'] = [];
                $eventLiveStream = $liveStreamInfo[$event['EventGroupId']] ?? $liveStreamInfo[$event['EventId']] ?? [];
                if (!empty($eventLiveStream)) {
                    foreach ($eventLiveStream as $type => $url) {
                        switch ($type) {
                            case 'flv':
                                $event['LiveStreaming'] = true;
                                $event['LiveStreamingUrl'] = $url;
                                break;
                            case 'm3u8':
                                $event['LiveStreamingMobile'] = true;
                                $event['LiveStreamingMobileUrl'] = $url;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getLiveEventInfo($sportIds, $betTypeIds, $periodIds, $oddsType, $orderBy,
                                     $competitionIds, $languageCode, $colorCode)
    {
        // 取得已對應賽事直播資訊 (eventId => liveInfo)
        $liveStreamInfo = $this->liveStreamRepo->getLiveStreamInfoBySportIds($sportIds);

        $imSport = new IMSport();
        $data = $imSport->getLiveEventInfo(
            $sportIds,
            $oddsType,
            false,
            $languageCode,
            $betTypeIds,
            $orderBy,
            $periodIds,
            null,
            null,
            $competitionIds);

        if (!empty($data['Sports'])) {
            foreach ($data['Sports'][0]['Events'] as &$event) {
                // 是否需要色碼 (聯盟名稱)
                if ($colorCode) { # 16進制, 數字越大顏色越淺
                    // 名稱轉16進制
                    $code = dechex(crc32($event['Competition']['CompetitionName']));
                    // 取6碼作為色碼 (hex color)
                    $code = substr($code, 0, 6);
                    // 取出RGB的hex
                    $rgbList = str_split($code, 2);
                    // 當顏色太深, 進行調色
                    foreach ($rgbList as &$rgb) {
                        if ($rgb < $colorCode) {
                            $rgb = dechex(hexdec($rgb) + hexdec($colorCode));
                        }
                    }
                    $code = join('', $rgbList);
                    $event['Competition']['CompetitionColor'] = $code;
                }

                // 回傳格式
                $event['LiveStreaming'] = $event['LiveStreamingMobile'] = false;
                $event['LiveStreamingUrl'] = $event['LiveStreamingMobileUrl'] = [];
                $eventLiveStream = $liveStreamInfo[$event['EventGroupId']] ?? $liveStreamInfo[$event['EventId']] ?? [];
                if (!empty($eventLiveStream)) {
                    foreach ($eventLiveStream as $type => $url) {
                        switch ($type) {
                            case 'flv':
                                $event['LiveStreaming'] = true;
                                $event['LiveStreamingUrl'] = $url;
                                break;
                            case 'm3u8':
                                $event['LiveStreamingMobile'] = true;
                                $event['LiveStreamingMobileUrl'] = $url;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getSelectedEventInfo($sportId, $eventIds, $oddsType, $languageCode, $betTypeIds, $periodIds)
    {
        // 取得已對應賽事直播資訊 (eventId => liveInfo)
        $liveStreamInfo = $this->liveStreamRepo->getLiveStreamInfoBySportIds([$sportId]);
        $imSport = new IMSport();
        $data = $imSport->getSelectedEventInfo(
            $sportId,
            $eventIds,
            $oddsType,
            false,
            false,
            $languageCode,
            $betTypeIds,
            $periodIds);

        if (!empty($data['Sports'])) {
            foreach ($data['Sports'][0]['Events'] as &$event) {
                // 回傳格式
                $event['LiveStreaming'] = $event['LiveStreamingMobile'] = false;
                $event['LiveStreamingUrl'] = $event['LiveStreamingMobileUrl'] = [];
                $eventLiveStream = $liveStreamInfo[$event['EventGroupId']] ?? $liveStreamInfo[$event['EventId']] ?? [];
                if (!empty($eventLiveStream)) {
                    foreach ($eventLiveStream as $type => $url) {
                        switch ($type) {
                            case 'flv':
                                $event['LiveStreaming'] = true;
                                $event['LiveStreamingUrl'] = $url;
                                break;
                            case 'm3u8':
                                $event['LiveStreamingMobile'] = true;
                                $event['LiveStreamingMobileUrl'] = $url;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }
}
