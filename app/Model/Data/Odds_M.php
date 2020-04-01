<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Odds_M extends Model
{
    protected $table = 'data_league';
    protected $guarded = [];
    protected $connection = 'core_master';
}
