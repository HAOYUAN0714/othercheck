<?php


namespace App\Repository;


use Illuminate\Support\Facades\DB;

class LiveStream
{
    protected $liveStreamMaster;
    protected $liveStreamSlave;

    public function __construct()
    {
        $this->liveStreamMaster = DB::connection('live_stream_master');
        $this->liveStreamSlave = DB::connection('live_stream_slave');
    }

    public function cacheLiveList($liveInfo)
    {
        try {
            // prevent sql injection
            $qm = [];
            $field = array_keys($liveInfo[0]);
            for ($i = 0; $i < count($field); $i++) {
                $qm[] = '?';
            }
            $qmKey = '(' . join(',', $qm) . ')';

            $values = $qmValues = [];
            foreach ($liveInfo as $index => $item) {
                $values = array_merge($values, array_values($item));
                $qmValues[] = $qmKey;
            }
            $sql = 'INSERT INTO live_list (' . join(',', $field) . ') VALUES ';
            $sql .= join(',', $qmValues);
            $sql .= ' ON DUPLICATE KEY UPDATE sport_id = VALUES(sport_id), event_date = VALUES(event_date), league_chs_name = VALUES(league_chs_name), league_eng_name = VALUES(league_eng_name), home_chs_name = VALUES(home_chs_name), home_eng_name = VALUES(home_eng_name), away_chs_name = VALUES(away_chs_name), away_eng_name = VALUES(away_eng_name), live_stream_flv = VALUES(live_stream_flv), live_stream_m3u8 = VALUES(live_stream_m3u8)';

            $connection = $this->liveStreamMaster;
            $connection->insert($sql, $values);
        } catch (\Exception $error) {
            throw new \Exception("更新直播賽事資訊錯誤 msg={$error->getMessage()}", 112033002);
        }
    }

    public function cacheEventList($eventInfo)
    {
        try {
            // prevent sql injection
            $qm = [];
            $field = array_keys($eventInfo[0]);
            for ($i = 0; $i < count($field); $i++) {
                $qm[] = '?';
            }
            $qmKey = '(' . join(',', $qm) . ')';

            $values = $qmValues = [];
            foreach ($eventInfo as $index => $item) {
                $values = array_merge($values, array_values($item));
                $qmValues[] = $qmKey;
            }
            $sql = 'INSERT INTO event_list (' . join(',', $field) . ') VALUES ';
            $sql .= join(',', $qmValues);
            $sql .= ' ON DUPLICATE KEY UPDATE sport_id = VALUES(sport_id), market = VALUES(market), event_date = VALUES(event_date), league_chs_name = VALUES(league_chs_name), home_chs_name = VALUES(home_chs_name), away_chs_name = VALUES(away_chs_name), league_eng_name = VALUES(league_eng_name), home_eng_name = VALUES(home_eng_name), away_eng_name = VALUES(away_eng_name)';

            $connection = $this->liveStreamMaster;
            $connection->insert($sql, $values);
        } catch (\Exception $error) {
            throw new \Exception("更新IM賽事資訊錯誤 msg={$error->getMessage()}", 112033002);
        }
    }

    public function getLiveList($startDate, $endDate)
    {
        $connection = $this->liveStreamSlave;
        $data = $connection->table('live_list as ll')
            ->leftJoin('mapping_list as ml', 'll.live_stream_id', 'ml.live_stream_id')
            ->select('ll.sport_id', 'll.live_stream_id', 'ml.im_event_id', 'll.is_correspond', 'll.event_date', 'll.league_chs_name', 'll.league_eng_name', 'll.home_chs_name', 'll.home_eng_name', 'll.away_chs_name', 'll.away_eng_name', 'll.live_stream_flv', 'll.live_stream_m3u8', 'll.created_at', 'll.updated_at')
            ->whereBetween('ll.event_date', [$startDate, $endDate])
            ->orderBy('event_date')
            ->get();

        // 解析物件
        $events = $eventData = [];
        foreach ($data as $event) {
            foreach ($event as $key => $value) {
                $eventData[$key] = $value;
            }
            $events[] = $eventData;
        }
        return $events;
    }

    public function getEventList($startDate, $endDate)
    {
        $connection = $this->liveStreamSlave;
        $data = $connection->table('event_list as el')
            ->leftJoin('mapping_list as ml', 'el.event_id', 'ml.im_event_id')
            ->select('el.sport_id', 'el.event_id', 'ml.live_stream_id', 'el.is_correspond', 'el.event_date', 'el.league_chs_name', 'el.league_eng_name', 'el.home_chs_name', 'el.home_eng_name', 'el.away_chs_name', 'el.away_eng_name', 'el.created_at', 'el.updated_at')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date');

        $data = $data->get();

        // 解析物件
        $events = $eventData = [];
        foreach ($data as $event) {
            foreach ($event as $key => $value) {
                $eventData[$key] = $value;
            }
            $events[] = $eventData;
        }
        return $events;
    }

    public function correspondLiveStream($imEventId, $liveEventId)
    {
        $connection = $this->liveStreamMaster;

        // 先確認對應ID是否存在
        $eventList = $connection->table('event_list')
            ->where('event_id', $imEventId);
        if ($eventList->get()->isEmpty()) {
            throw new \Exception("查無IM賽事ID: {$imEventId}", 112033003);
        }

        $liveList = $connection->table('live_list')
            ->where('live_stream_id', $liveEventId);
        if ($liveList->get()->isEmpty()) {
            throw new \Exception("查無Live賽事ID: {$liveEventId}", 112033003);
        }

        // 檢查賽事是否已對應
        $mappingList = $connection->table('mapping_list')
            ->where('im_event_id', $imEventId)
            ->orWhere('live_stream_id', $liveEventId)
            ->first();
        if (!is_null($mappingList)) {
            $imEventId = $mappingList->im_event_id;
            $liveEventId = $mappingList->live_stream_id;
            throw new \Exception("IM賽事: {$imEventId} 已對應 Live: {$liveEventId}, 請先取消對應", 112033007);
        }

        // 更新賽事對應狀態
        $eventList->update(['is_correspond' => true]);
        $liveList->update(['is_correspond' => true]);
        $connection->table('mapping_list')
            ->insert([
                'im_event_id' => $imEventId,
                'live_stream_id' => $liveEventId
            ]);
    }

    public function cancelCorrespondLiveStream($eventId, $type)
    {
        $connection = $this->liveStreamMaster;

        // 先至對應表取得對應ID
        $mappingList = $connection->table('mapping_list')
            ->where($type, $eventId);

        $mappingData = $mappingList->first();
        if (is_null($mappingData)) {
            throw new \Exception("{$type}: {$eventId} 無對應賽事", 112033003);
        }

        // 取得已對應直播賽事ID
        $imEventId = $mappingData->im_event_id;
        $liveEventId = $mappingData->live_stream_id;

        // 移除對應紀錄
        $mappingList->delete();

        // 更新賽事對應狀態
        $connection->table('event_list')
            ->where('event_id', $imEventId)
            ->update(['is_correspond' => false]);

        $connection->table('live_list')
            ->where('live_stream_id', $liveEventId)
            ->update(['is_correspond' => false]);

        return [$imEventId, $liveEventId];
    }

    // 取得已對應賽事直播資訊
    public function getLiveStreamInfoBySportIds($sportIds)
    {
        $connection = $this->liveStreamSlave;
        $data = $connection->table('mapping_list as ml')
            ->join('live_list as ll', 'ml.live_stream_id', 'll.live_stream_id')
            ->select('ml.im_event_id', 'll.live_stream_flv', 'll.live_stream_m3u8')
            ->whereIn('sport_id', $sportIds)->get()
            ->keyBy('im_event_id');

        // 解析物件
        $liveStreamInfo = [];
        foreach ($data as $eventId => $info) {
            $liveStreamFlv = json_decode($info->live_stream_flv, true);
            $liveStreamM3u8 = json_decode($info->live_stream_m3u8, true);
            // 檢查兩格式中的串流狀態, 若關閉則不回傳
            foreach ([&$liveStreamFlv, &$liveStreamM3u8] as &$type) {
                foreach ($type as $index => $source) {
                    if (!$source['Status']) {
                        unset($type[$index]);
                    }
                }
                $type = array_values($type); # 重置索引
            }
            // 若無直播網址則不返回
            if (!empty($liveStreamFlv)) {
                $liveStreamInfo[$eventId]['flv'] = $liveStreamFlv;
            }
            if (!empty($liveStreamM3u8)) {
                $liveStreamInfo[$eventId]['m3u8'] = $liveStreamM3u8;
            }
        }
        return $liveStreamInfo;
    }
}
