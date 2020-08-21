<?php

namespace N949mac\StopWords\Facades;

use Illuminate\Support\Facades\Facade;

class StopWords extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stop-words';
    }
}
