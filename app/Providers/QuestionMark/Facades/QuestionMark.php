<?php

namespace App\Providers\QuestionMark\Facades;

use Illuminate\Support\Facades\Facade;

class QuestionMark extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'question_mark';
    }
}
