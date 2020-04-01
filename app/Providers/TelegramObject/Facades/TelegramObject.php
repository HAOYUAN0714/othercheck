<?php

namespace App\Providers\TelegramObject\Facades;

use Illuminate\Support\Facades\Facade;

class TelegramObject extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'telegram_object';
    }
}
