<?php

namespace App\Model\Mapping;

use Illuminate\Database\Eloquent\Model;

class League_M extends Model
{
    protected $table = 'data_league';
    protected $guarded = [];
    protected $connection = 'core_master';
}
