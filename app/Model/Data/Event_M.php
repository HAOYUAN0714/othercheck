<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Event_M extends Model
{
    protected $table = 'data_event';
    protected $guarded = [];
    protected $connection = 'core_master';
}
