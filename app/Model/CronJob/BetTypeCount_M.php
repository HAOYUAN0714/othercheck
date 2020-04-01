<?php

namespace App\Model\CronJob;

use Illuminate\Database\Eloquent\Model;

class BetTypeCount_M extends Model
{
    protected $table = 'cron_bet_type_count';
    protected $guarded = [];
    protected $connection = 'core_master';
}
