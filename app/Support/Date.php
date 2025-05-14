<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use DateTimeZone;

class Date
{
    private static ?DateTimeZone $timezone_default;

    public function __construct()
    {
        self::$timezone_default = new DateTimeZone(\date_default_timezone_get());
    }

    public static function set_timezone_default(DateTimeZone $timezone): void
    {
        self::$timezone_default = $timezone;
    }

    /**
     * Localize date based on datetime and timezone
     *
     * @param  string|null  $time_zone  default from config
     */
    public function localize(DateTimeInterface|string $date_time, ?string $time_zone = null): CarbonImmutable
    {
        $time_zone = $time_zone ? new DateTimeZone($time_zone) : self::$timezone_default;
        if (is_string($date_time)) {
            $date_time = CarbonImmutable::parse($date_time);
        }

        return CarbonImmutable::createFromInterface($date_time)->setTimezone($time_zone);
    }

    /**
     * Undocumented function
     *
     * @param  string|null  $format  default 'Y-m-d H:i:s'
     */
    public function localize_str(DateTimeInterface|string $date_time, ?string $time_zone = null, ?string $format = null): string
    {
        $format = $format ?? 'Y-m-d H:i:s';

        return $this->localize($date_time, $time_zone)->format($format);
    }

    /**
     * Undocumented function
     *
     * @param  string|null  $format  default 'd F Y, H:i
     */
    public function localize_str_human(DateTimeInterface|string $date_time, ?string $time_zone = null, ?string $format = null): string
    {
        $format = $format ?? 'd F Y, H:i';

        return $this->localize_str($date_time, $time_zone, $format);
    }
}
