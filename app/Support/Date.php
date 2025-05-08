<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use DateTimeInterface;

class Date
{
    public function localize(DateTimeInterface|string $date_time, ?string $time_zone = null): CarbonImmutable
    {
        $time_zone = $time_zone ?? \config('webcms.timezone_local');
        if (is_string($date_time)) {
            $date_time = CarbonImmutable::parse($date_time);
        }

        return CarbonImmutable::createFromInterface($date_time)->setTimezone($time_zone);
    }
}
