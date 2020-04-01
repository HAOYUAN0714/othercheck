<?php

namespace App\Model\CronJob;

use Illuminate\Database\Eloquent\Model;

class WidgetEvent_M extends Model
{
    protected $table = 'cron_widget_event';
    protected $guarded = [];
    protected $connection = 'core_master';
}
