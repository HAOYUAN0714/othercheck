<?php


namespace App\Repository\CronJob;

use App\Model\CronJob\WidgetEvent_M;
use App\Model\CronJob\WidgetEvent_S;

class WidgetEvent
{
    protected $widgetEventMaster;
    protected $widgetEventSlave;

    public function __construct()
    {
        $this->widgetEventMaster = new WidgetEvent_M();
        $this->widgetEventSlave = new WidgetEvent_S();
    }

    public function storeWidgetEventList($eventList)
    {
        $connection = $this->widgetEventMaster;
        $connection->updateOrCreate(['cron_date' => $eventList['cron_date']], $eventList);
    }

    public function getWidgetEventList($languageCode)
    {
        $connection = $this->widgetEventSlave;
        date_default_timezone_set('GMT');
        $date = date("Y-m-d", strtotime('-4 hour'));
        $data = $connection->select('lang_' . $languageCode)
            ->where('cron_date', $date)->first();

        $data = $data ? $data->toArray() : [];

        return $data;
    }
}
