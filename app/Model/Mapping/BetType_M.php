<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BetType_M extends Model
{
    protected $table = 'data_bet_type';
    protected $guarded=[];
    protected $connection='core_master';
}
