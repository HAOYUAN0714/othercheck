<?php

namespace App\Model\Mapping;

use Illuminate\Database\Eloquent\Model;

class Sport_M extends Model
{
    protected $table = 'data_sport';
    protected $guarded = [];
    protected $connection = 'core_master';
}
