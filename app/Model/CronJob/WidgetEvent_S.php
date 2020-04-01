<?php

namespace App\Model\CronJob;

use Illuminate\Database\Eloquent\Model;

class WidgetEvent_S extends Model
{
    protected $table = 'cron_widget_event';
    protected $guarded = [];
    protected $connection = 'core_slave';
}
