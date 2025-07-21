<?php

namespace App\Support\Facades;

use App\Support\Date as SupportDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Facade;

/**
 * Undocumented class
 *
 * @method CarbonImmutable localize(DateTimeInterface|string $date_time, ?string $time_zone = null)
 * @method string localize_str(DateTimeInterface|string $date_time, ?string $time_zone = null, ?string $format = null )
 * @method string localize_str_human(DateTimeInterface|string $date_time, ?string $time_zone = null, ?string $format = null)
 */
class Date extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SupportDate::class;
    }
}
