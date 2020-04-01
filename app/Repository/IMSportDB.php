<?php


namespace App\Repository;

/**
 * Include Model
 */

use App\Model\CronJob\BetTypeCount_M;
use App\Model\CronJob\BetTypeCount_S;
use Illuminate\Support\Facades\DB;


class IMSportDB
{
    protected $betTypeCountMaster;
    protected $betTypeCountSlave;
    protected $coreMaster;
    protected $coreSlave;

    public function __construct()
    {
        $this->betTypeCountMaster = new BetTypeCount_M();
        $this->betTypeCountSlave = new BetTypeCount_S();
        $this->coreMaster = DB::connection('core_master');
        $this->coreSlave = DB::connection('core_slave');
    }

    public function storeBetTypeCountV2($betTypeCount)
    {
        $field = array_keys($betTypeCount[0]);
        // prevent sql injection
        list($qmValues, $values) = \QuestionMark::makeQM($field, $betTypeCount);

        $sql = 'INSERT INTO cron_bet_type_count (' . join(',', $field) . ') VALUES ';
        $sql .= join(',', $qmValues);
        $sql .= ' ON DUPLICATE KEY UPDATE data = VALUES(data)';

        $connection = $this->coreMaster;
        $connection->insert($sql, $values);
    }

    public function getBetTypeCountV2($key)
    {
        $connection = $this->coreSlave;
        $data = $connection->table('cron_bet_type_count')
            ->select('data')
            ->where('count_key', $key)
            ->first();
        return $data->data;
    }

    // 儲存玩法計數進資料庫
    public function storeBettypeCount($data, $key)
    {
        $connection = $this->betTypeCountMaster;
        $isTomorrow = date("H:i:s", strtotime('-4 hour'));   // 判斷目前美東時間

        if (isset($data)) {
            date_default_timezone_set('GMT');
            $date = date("Y-m-d", strtotime('-4 hour')) . '-' . $key;
            $response = $connection->where('name', $date)->get()->toArray();
            if ($response) {
                // 如果資料庫已有今日的資料，更新資料
                $connection->where('name', $date)->update(['data' => json_encode($data)]);
            } else {
                // 如果資料庫沒有今日的資料，新增一筆
                $connection->insert([
                    'name' => $date,
                    'data' => json_encode($data)
                ]);
            }
            $isOK = true;
        } else {
            $isOK = false;
        }

        // 當接近隔天凌晨，先增加一筆明天的資料
        if ($isTomorrow >= "23:58:00") {
            $connection->insert([
                'name' => date("Y-m-d", strtotime('+1 day -4 hour')) . '-' . $key,
                'data' => json_encode($data)
            ]);
        }

        return response()->json($isOK);
    }

    // 讀取資料庫中的玩法計數
    public function getBettypeCount($name)
    {
        $connection = $this->betTypeCountSlave;

        $data = $connection->select('data')->orderby('id', 'desc')
            ->where('name', $name)->first();

        // 判斷是否有資料
        $data = $data ? $data->toArray() : [];
        return $data;
    }
}
