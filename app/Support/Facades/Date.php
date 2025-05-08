<?php

namespace App\Support\Facades;

use App\Support\Date as SupportDate;
use Illuminate\Support\Facades\Facade;

class Date extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SupportDate::class;
    }
}
